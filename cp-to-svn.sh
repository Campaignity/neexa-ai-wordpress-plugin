tag=$1
# Check if tag is empty or equal to 0
if [ -z "$tag" ] || [ "$tag" = "0" ]; then
    echo "Tag is empty or zero"
else
    cp -r ./* ./../svn/trunk
    cp -r ./* ./../svn/tags/$tag
fi