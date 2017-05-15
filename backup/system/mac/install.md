# MAC


## 去除安装应用来源限制

`sudo spctl --master-disable`

## 让Finder显示隐藏文件

`defaults write com.apple.finder AppleShowAllFiles YES; killall Finder`


## 禁止生成.DS_Store

```
defaults write com.apple.desktopservices DSDontWriteNetworkStores -bool true
#or
defaults write com.apple.desktopservices DSDontWriteNetworkStores true
```


## Remove all DS_Store files

`sudo find / -name ".DS_Store" -depth -exec rm {} \;`


## App

https://itunes.apple.com/cn/app/xcode/id497799835

http://www.oracle.com/technetwork/java/javase/downloads/jdk8-downloads-2133151.html

https://hbang.ws/apps/termhere/

https://itunes.apple.com/cn/app/id921458519

https://itunes.apple.com/cn/app/app-shredder-find-remove-applications/id1033808943

http://oldj.github.io/SwitchHosts/#cn

https://www.jetbrains.com/webstorm/download/#section=mac

http://idea.lanyus.com

https://www.dingtalk.com

http://xclient.info/s/office-for-mac-2016.html

http://xclient.info/s/airmail.html

https://itunes.apple.com/cn/app/ou-lu-ying-yu-ci-dian-free/id434350458

https://github.com/getlantern/forum/issues/833

https://www.google.com/chrome/browser/canary.html

## 快捷键和手势

https://support.apple.com/zh-cn/HT201236

windows和mac的按键对照表
https://support.microsoft.com/en-us/help/970299/keyboard-mappings-using-a-pc-keyboard-on-a-macintosh

https://support.apple.com/zh-cn/HT204895

# brew


## https://brew.sh/

`/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"`


## https://www.iterm2.com/

```
brew install Caskroom/cask/iterm2 wget git python

pip install powerline-status
```

https://github.com/powerline/fonts

cd到install.sh文件所在目录执行`./install.sh`指令安装所有Powerline字体

iTerm 2的Preferences——Profiles——Text——Regular Font/Non-ASCII Font设置成 Powerline的字体，比如Meslo LG M DZ

iTerm 2的Preferences——Profiles——Colors——Load Presets——Solarized Dark



# zsh


`brew install zsh zsh-completions`


## 修改默认shell为zsh

`chsh -s /bin/zsh`


## 当前默认bash

`echo $SHELL`


## 查看zsh版本

`zsh --version`


## shell list

`cat /etc/shells`


To activate these completions, add the following to your .zshrc:

`fpath=(/usr/local/share/zsh-completions $fpath)`

You may also need to force rebuild `zcompdump`:

`rm -f ~/.zcompdump; compinit`

Additionally, if you receive "zsh compinit: insecure directories" warnings when attempting
to load these completions, you may need to run this:

`chmod go-w '/usr/local/share'`



# oh-my-zsh

https://github.com/robbyrussell/oh-my-zsh

`sh -c "$(curl -fsSL https://raw.githubusercontent.com/robbyrussell/oh-my-zsh/master/tools/install.sh)"`


ZSH_THEME="agnoster"


https://github.com/robbyrussell/oh-my-zsh/wiki/Cheatsheet

https://github.com/robbyrussell/oh-my-zsh/wiki/Plugins

`git clone https://github.com/zsh-users/zsh-syntax-highlighting.git ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting`

plugins=(git zsh-syntax-highlighting common-aliases web-search sudo node npm nvm brew osx)

`source ~/.zshrc`

`upgrade_oh_my_zsh`



# web


`brew install vim`

`brew install nvm`

You should create NVM's working directory if it doesn't exist:

`mkdir ~/.nvm`

Add the following to ~/.zshrc or your desired shell configuration file:

```
export NVM_DIR="$HOME/.nvm"
. "/usr/local/opt/nvm/nvm.sh"
```

`nvm install node`

`npm install -g nrm --registry=https://registry.npm.taobao.org/ && nrm use taobao`


## tldr node版 Installing

```
npm install -g tldr

#If you have trouble with the post-install script, try the following commands 如果安装出错,换下面命令，—ignore-scripts 长选项，跳过安装脚本错误 :

npm install -g --ignore-scripts tldr

tldr --update
```

## git

1. 取消global

    `git config --global --unset user.name && git config --global --unset user.email`


2. 设置每个项目repo的user

    `git config user.name "x" && git config user.email "x@x.com"`


## 配置.ssh/config（如果没有就新建一个）

`chmod -R 700 ~/.ssh`

`vim ~/.ssh/config`

```
Host *
  UseKeychain yes
  AddKeysToAgent yes
  #ssh连接时将自动进行添加，即可免输入yes进行known_hosts添加
  StrictHostKeychecking no
  #自动更新known_hosts 
  UserKnownHostsFile /dev/null

#host alias
Host github
    #host ip or domain name
    HostName github.com 
    IdentityFile ~/.ssh/id_rsa_x
```

```
#https://help.github.com/articles/connecting-to-github-with-ssh/
ssh-add -l
eval "$(ssh-agent -s)"
ssh-add -K ~/.ssh/id_rsa

#https://github.com/jirsbek/SSH-keys-in-macOS-Sierra-keychain
curl -o ~/Library/LaunchAgents/ssh.add.a.plist https://raw.githubusercontent.com/jirsbek/SSH-keys-in-macOS-Sierra-keychain/master/ssh.add.a.plist

#验证服务器和客户端是否握手成功 可能需要有ssh文件传输操作才能永久保存私钥
ssh -T x@x.com
```

## services

https://github.com/Homebrew/homebrew-services


```
#Installation

brew tap homebrew/services


#List all services managed by brew services

brew services list


#Run/start/stop/restart all available services

brew services run|start|stop|restart --all


brew install nginx
brew services start nginx

#Docroot is: /usr/local/var/www

#The default port has been set in /usr/local/etc/nginx/nginx.conf to 8080 so that nginx can run without sudo.

#nginx will load all files in /usr/local/etc/nginx/servers/.

#nginx: [error] open() "/usr/local/var/run/nginx.pid" failed
sudo nginx

#nginx: [emerg] bind() to 0.0.0.0:80 failed
sudo nginx -s reload

#check nginx config syntax
sudo nginx -t

brew install mysql
brew services start mysql
brew services stop mysql
brew services restart mysql
```


## python

```
pip install virtualenv

brew install autoenv
```

