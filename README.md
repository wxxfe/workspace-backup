# workspace-backup

######1.取消global
```git

git config --global --unset user.name
git config --global --unset user.email

```

######2.设置每个项目repo的自己的user.email
```git

git config  user.email "x@x.com"
git config  user.name "x"

```

```
vim ~/.ssh/config
#配置.ssh/config（如果没有就新建一个）
```

```
host *
#选项“Host”只对能够匹配后面字串的zhu'j主机 有效。“*”表示所有的计算机。
StrictHostKeyChecking no
#ssh连接时将自动进行添加，即可免输入yes进行known_hosts添加，
```
