<?php

/**
 * Called when some communication is needed with Neexa API
 *
 * @link       https://neexa.co
 * @since      1.0.0
 *
 * @package    Neexa_Ai
 * @subpackage Neexa_Ai/includes
 */

/**
 * handles api consumption
 *
 * This class defines all access points available to the Neexa AI API
 *
 * @since      1.0.0
 * @package    Neexa_Ai
 * @subpackage Neexa_Ai/includes
 * @author     Neexa <hello@neexa.co>
 */


class Neexa_Ai_Api_Consumer
{

    const UNAUTHENTICATED_ERROR_RESPONSE = "Unauthenticated.";
    private $api_base_url;
    private $guest_token;
    private $api_key;


    public function __construct()
    {
        global $neexa_ai_config;

        $this->api_key = get_option('neexa_ai_access_token');
        $this->api_base_url = $neexa_ai_config['api-host'];
        $this->guest_token = $neexa_ai_config['guest_token'];
    }

    private function handle_unauthenticated_error_response()
    {
        update_option('neexa_ai_access_token', null);
    }

    /**
     * Makes a GET request to the Neexa AI API
     */
    private function get($endpoint, $params = [])
    {
        $url = add_query_arg($params, trailingslashit($this->api_base_url) . ltrim($endpoint, '/'));

        try {
            $response = wp_remote_get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_key,
                    'Accept'        => 'application/json',
                ]
            ]);

            if (is_wp_error($response)) {
                return ['error' => $response->get_error__message()];
            }

            $body = wp_remote_retrieve_body($response);

            $body = json_decode($body, true);



            if (wp_remote_retrieve_response_code($response) !== 200) {
                $errorMessage = $body["message"] ?? "Data fetching failed. Reload page to retry...";

                if ($errorMessage == self::UNAUTHENTICATED_ERROR_RESPONSE) {
                    $this->handle_unauthenticated_error_response();
                }

                return ['error' =>  $errorMessage];
            }

            return [
                'success' => true,
                'data' => $body
            ];
        } catch (\Throwable $th) {
            return ['error' =>  "Data fetching failed. Reload page to retry..."];
        }
    }

    /**
     * Fetches total conversations today, active CRM follow-ups, and campaigns this month
     */
    public function get_ai_agent_summary_stats($agent_id)
    {
        $whole_of_today  = $this->get_today_date_range();

        $whole_of_this_month = $this->get_this_month_date_range();

        return $this->get("/v1/wp-plugin/chat_widgets/$agent_id/summary_stats", [
            "filter[start_of_month]" => $whole_of_this_month[0],
            "filter[start_of_today]" => $whole_of_today[0],
        ]);
    }

    /**
     * Fetch AI agent and feature statuses (data collection, CRM, channels)
     */
    public function get_ai_agent_info($agent_id, $params = [])
    {
        return $this->get("/v1/wp-plugin/chat_widgets/{$agent_id}", $params);
    }

    /**
     * Fetch AI agent and feature statuses (data collection, CRM, channels)
     */
    public function get_ai_agents($params = [])
    {
        return $this->get("/v1/wp-plugin/chat_widgets", $params);
    }

    private function get_today_date_range()
    {
        // Get WordPress timezone
        $timezone_string = get_option('timezone_string');
        $timezone = $timezone_string ? new DateTimeZone($timezone_string) : wp_timezone(); // fallback to gmt_offset

        // Start of the day in site timezone
        $start = new DateTime('today', $timezone);

        // End of the day in site timezone
        $end = new DateTime('tomorrow', $timezone);
        $end->modify('-1 second'); // Set to 23:59:59

        // Convert to UTC
        $start->setTimezone(new DateTimeZone('UTC'));
        $end->setTimezone(new DateTimeZone('UTC'));

        // Format to UTC ISO8601 (e.g., 2025-04-13T00:00:00Z)
        $start_utc = $start->format('Y-m-d\TH:i:s\Z');
        $end_utc = $end->format('Y-m-d\TH:i:s\Z');

        return [
            $start_utc,
            $end_utc
        ];
    }

    private function get_this_month_date_range()
    {
        // Get WordPress timezone
        $timezone_string = get_option('timezone_string');
        $timezone = $timezone_string ? new DateTimeZone($timezone_string) : wp_timezone(); // fallback to gmt_offset

        // Start of the current month in site timezone
        $start_of_month = new DateTime('first day of this month midnight', $timezone);

        // End of the current month in site timezone
        $end_of_month = new DateTime('last day of this month 23:59:59', $timezone);

        // Convert both to UTC
        $start_of_month->setTimezone(new DateTimeZone('UTC'));
        $end_of_month->setTimezone(new DateTimeZone('UTC'));

        // Format to UTC ISO8601
        $start_of_month_utc = $start_of_month->format('Y-m-d\TH:i:s\Z');
        $end_of_month_utc = $end_of_month->format('Y-m-d\TH:i:s\Z');

        // Return as associative array
        return [
            $start_of_month_utc,
            $end_of_month_utc,
        ];
    }

    public function send_feedback_to_platform($data)
    {
        try {
            $payload = [
                'data' => [
                    'type' => 'plugin_feedback',
                    'attributes' => $data,
                ],
            ];

            $response = wp_remote_post(trailingslashit($this->api_base_url) . 'v1/auth_agnostic_plugin_feedback', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_key,
                    'X-Plugin-Token' => $this->guest_token,
                    'Content-Type'  => 'application/json',
                ],
                'body' => json_encode($payload),
                'timeout' => 10,
            ]);

            if (is_wp_error($response)) {
                return false;
            }

            $res = wp_remote_retrieve_body($response);
            // print_r($res);

            $code = wp_remote_retrieve_response_code($response);
            if ($code !== 200 && $code !== 201) {
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }


    public function destroy_auth()
    {
        try {
            wp_remote_request(trailingslashit($this->api_base_url) . 'v1/wp-plugin/api_keys', [
                'method'    => 'DELETE',
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_key,
                    'Content-Type'  => 'application/json',
                ],
            ]);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
