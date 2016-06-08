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
#host alias
Host phz 
    #host ip or domain name
    HostName github.com 
    #ssh连接时将自动进行添加，即可免输入yes进行known_hosts添加
    StrictHostKeychecking=no
    #自动更新known_hosts 
    UserKnownHostsFile=/dev/null
    #key 
    IdentityFile ~/.ssh/id_rsa_panhezeng_github

```

```
ssh -T x@x.com
#验证服务器和客户端是否握手成功
```

