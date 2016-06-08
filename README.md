# workspace-backup

######1.取消global
```
git config --global --unset user.name
git config --global --unset user.email
```

######2.设置每个项目repo的user.
```
git config user.name "x"
git config user.email "x@x.com"
```

```
vim ~/.ssh/config
#配置.ssh/config（如果没有就新建一个）
```

```
Host *
#host ip or domain name ,“*”表示所有的计算机。
HostName x
#主机名
StrictHostKeychecking=no
#ssh连接时将自动进行添加，即可免输入yes进行known_hosts添加
UserKnownHostsFile=/dev/null
#自动更新known_hosts
IdentityFile ~/.ssh/id_rsa_x
```

```
ssh -T x@x.com
#验证服务器和客户端是否握手成功
```

