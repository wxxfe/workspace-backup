if &compatible | set nocompatible | endif " Avoid side effects if `nocp` already set

let mapleader = ";"
let g:mapleader = ";"

"""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""
" => Nerd Tree
"""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""
let g:NERDTreeWinPos = "left"
let NERDTreeShowHidden=1
map <leader>n :NERDTreeToggle<cr>

colorscheme molokai
