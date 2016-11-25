echo 'branch fullname =  type f (features) or b (bugfix) or h (hotfix)/name'
read -p 'branch fullname : ' fullname

echo $fullname

read -p 'branch c (create) or d (delete) : ' start

if [ "$start" == "c" ]; then
    git fetch -pa && git pull &&  git branch -a
    read -p 'branch base: ' base
    echo "${fullname} ${base} start create"
    git checkout -b $fullname $base && git push origin $fullname && git push --set-upstream origin $fullname && git fetch -p && git branch -a
else
    echo "${fullname} start delete"
    git checkout develop && git pull && git branch -d $fullname && git push origin --delete $fullname && git fetch -p && git branch -a
fi

