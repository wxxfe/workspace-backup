read -p 'branch type f (features) or b (bugfix) : ' type
read -p 'branch name : ' name

if [ "$type" == "f" ]; then
    type="features"
else
    type="bugfix"
fi

fullname="${type}/${name}"
echo $fullname

read -p 'branch c (create) or d (delete) : ' start

if [ "$start" == "c" ]; then
    echo "${fullname} start create"
    git checkout develop && git pull && git branch $fullname && git checkout $fullname && git push origin $fullname && git push --set-upstream origin $fullname && git fetch -p && git branch -a
else
    echo "${fullname} start delete"
    git checkout develop && git pull && git branch -d $fullname && git push origin --delete $fullname && git fetch -p && git branch -a
fi

