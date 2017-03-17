echo 'branch fullname prefix : features/ or bugfix/ or hotfix/'
read -p 'branch fullname : ' fullname

echo $fullname

read -p 'branch c (create) or d (delete) : ' start

if [ "$start" == "c" ]; then
    git fetch -pa && git pull && git branch -a | grep -E 'develop|master' && git describe --tags `git rev-list --tags --max-count=1`
    read -p 'branch base: origin/develop or ? ' base
    echo "${fullname} ${base} start create"
    git checkout -b $fullname $base && git push origin $fullname && git push --set-upstream origin $fullname && git fetch -pa && git branch -a
else
    echo "${fullname} start delete"
    git checkout develop && git pull && git branch -d $fullname && git fetch -pa && git branch -a && git push origin --delete $fullname && git fetch -pa && git branch -a
fi

