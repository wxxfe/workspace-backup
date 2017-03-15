# brew


## https://brew.sh/

`/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"`


## https://www.iterm2.com/

```
brew install Caskroom/cask/iterm2

brew install wget

brew install git

pip install powerline-status
```

cd到install.sh文件所在目录执行./install.sh指令安装所有Powerline字体

iTerm 2的Preferences——Profiles——Text——Regular Font/Non-ASCII Font设置成 Powerline的字体，比如Meslo LG M DZ

iTerm 2的Preferences——Profiles——Colors——Load Presets——Solarized Dark



# mac


## 让Finder显示隐藏文件

`defaults write com.apple.finder AppleShowAllFiles YES; killall Finder`


## 禁止生成.DS_Store

```
defaults write com.apple.desktopservices DSDontWriteNetworkStores -bool true
defaults write com.apple.desktopservices DSDontWriteNetworkStores true
```


## Remove all DS_Store files

`sudo find / -name ".DS_Store" -depth -exec rm {} \;`


## 去除安装应用来源限制

`sudo spctl --master-disable`



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


## https://github.com/robbyrussell/oh-my-zsh

`sh -c "$(curl -fsSL https://raw.githubusercontent.com/robbyrussell/oh-my-zsh/master/tools/install.sh)"`


ZSH_THEME="agnoster"



# oh-my-zsh

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


`npm install -g nrm --registry=https://registry.npm.taobao.org/`


## tldr node版 Installing

```
npm install -g tldr

#If you have trouble with the post-install script, try the following commands 如果安装出错,换下面命令，—ignore-scripts 长选项，跳过安装脚本错误 :

npm install -g --ignore-scripts tldr

tldr --update
```


1. 取消global

```
git config --global --unset user.name
git config --global --unset user.email
```

2. 设置每个项目repo的user.

```
git config user.name "x"
git config user.email "x@x.com"

```

## 配置.ssh/config（如果没有就新建一个）
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
eval "$(ssh-agent -s)"
ssh-add -K ~/.ssh/id_rsa
```

## 验证服务器和客户端是否握手成功
`ssh -T x@x.com`
