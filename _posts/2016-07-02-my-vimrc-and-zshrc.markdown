---
layout: post
title: "我的vimrc和zshrc配置"
date: "2016-07-02"
---

Vim跟zsh(配合oh-my-zsh)是\*unix生态下的两大神器。如果懂得把vim跟zsh配置好，不仅能打造一个舒适的开发环境，而且大大提高开发效率。

### Vimrc 
{% highlight vim %}

" Vundle automatical installation
  let iCanHazVundle=1
  let vundle_readme=expand('~/.vim/bundle/Vundle.vim/README.md')
  if !filereadable(vundle_readme)
  echo "Installing Vundle for current user"
  silent !mkdir -p ~/.vim/bundle
  silent !git clone https://github.com/gmarik/Vundle.vim.git ~/.vim/bundle/Vundle.vim
  let iCanHazVundle=0
  endif

  " Vim basic setting

  " Use dark background and enable syntax highlight
  set nocompatible
  syntax enable
  set background=dark

  " Highlight match case
  set showmatch

  "command autocomplete suggestions
  set wildmenu
  set wildmode=longest:full,full

  "scrolling
  set scrolloff=5
  set backspace=indent,eol,start

  set showcmd		  " Show (partial) command in status line.
  set showmatch		" Show matching brackets.
  set ignorecase	" Do case insensitive matching
  set smartcase		" Do smart case matching
  set incsearch		" Incremental search
  set autowrite		" Automatically save before commands like :next and :make
  set hidden		  " Hide buffers when they are abandoned
  set number 
  set formatoptions-=cro " Disable continue comment

  " Don't use .swp files, git commit a lot and log your changes
  set nobackup
  set noswapfile

  "Hightlight search
  set hlsearch

  " folding feature setting
  set foldmethod=marker
  set foldlevel=0
  set modelines=1

  " Default indetation
  " size of a hard tabstop
  set autoindent
  set tabstop=2
  " Always uses spaces instead of tab characters
  set expandtab
  " size of an indent " 
  set shiftwidth=2

  " Indetation setting according filetype
  autocmd FileType php setlocal ts=4 sts=4 sw=4 expandtab
  autocmd FileType html setlocal ts=4 sts=4 sw=4 expandtab
  autocmd FileType ruby setlocal ts=2 sts=2 sw=2 expandtab

  " Source a global configuration file if available
  if filereadable("/etc/vim/vimrc.local")
  source /etc/vim/vimrc.local
  endif

  " Vundle plugin management
  filetype off                  " required

  " set the runtime path to include Vundle and initialize
  set rtp+=~/.vim/bundle/Vundle.vim
  call vundle#begin()
  " alternatively, pass a path where Vundle should install plugins
  "call vundle#begin('~/some/path/here')

  " let Vundle manage Vundle, required and keep Plugin commands between vundle#begin/end.

  Plugin 'gmarik/Vundle.vim'
  Plugin 'rstacruz/sparkup', {'rtp': 'vim/'}
  Plugin 'paradigm/vim-multicursor'
  Plugin 'tpope/vim-fugitive'
  Plugin 'Valloric/YouCompleteMe'
  Plugin 'scrooloose/nerdtree'
  Plugin 'bling/vim-airline'
  Plugin 'ryanoasis/vim-webdevicons'
  Plugin 'ervandew/supertab'
  Plugin 'vim-ruby/vim-ruby'
  Plugin 'tpope/vim-endwise'
  Plugin 'm2ym/rsense'
  Plugin 'tpope/vim-repeat'
  Plugin 'tpope/vim-rails'
  Plugin 'tomtom/tcomment_vim'
  Plugin 'MarcWeber/vim-addon-mw-utils'
  Plugin 'tomtom/tlib_vim'
  Plugin 'garbas/vim-snipmate'
  Plugin 'ecomba/vim-ruby-refactoring'

  " snippets
  Plugin 'honza/vim-snippets'

  " Fast navigate file tool
  Plugin 'kien/ctrlp.vim'

  "auto saves file, quite useful when I choose not to use swap files
  Plugin 'vim-scripts/vim-auto-save'

  Plugin 'altercation/vim-colors-solarized'

  "Vim substitute preview tool
  Plugin 'osyo-manga/vim-over'

  "Ack 
  Plugin 'mileszs/ack.vim'

  "easymotion
  Plugin 'easymotion/vim-easymotion'
  " Autoclose
  Plugin 'jiangmiao/auto-pairs'

  Plugin 'Chiel92/vim-autoformat'

  " All of your Plugins must be added before the following line
  call vundle#end()            " required

  " File level setting 
  filetype plugin indent on    " required
  "open a NERDTree automatically when vim starts up if no files were specified?
  autocmd StdinReadPre * let s:std_in=1
  autocmd VimEnter * if argc() == 0 && !exists("s:std_in") | NERDTree | endif
  " File type icon
  set guifont=Literation\ Mono\ Powerline\ Plus\ Nerd\ File\ Types:h11
  let g:snipMate = {}
  let g:snipMate.scope_aliases = {}
  let g:snipMate.scope_aliases['ruby'] = 'ruby,rails'

  "Mapping Key
  map t ^
  nnoremap 4 $
  nnoremap rf gg^vG^=
  map ; :
  map m :set number<CR>
  map cm :set nonumber<CR>
  nnoremap fp :OverCommandLine<CR>%s/
  map qi :q!
  map <C-a> ggVG
  map <C-c> y:call system("pbcopy", getreg("\""))<CR>
  map vp :vsplit

  " For rails-vim 
  map em :Emodel
  map ev :Eview
  map ec :Econtroller
  map ej :Ejavascript
  map es :Estylesheet
  map ei :Eimages
  map cap ca(
  map caq ca"
  map cip ci(
  map ciq ci"
  map ct c^
  map cb c$
  nnoremap dt d^
  nnoremap db d$
  nnoremap <Space> <C-d> 
  nnoremap fs :CtrlPMRU<CR>
  nnoremap <leader>t :NERDTreeToggle<CR>
  nmap s <Plug>(easymotion-s2)
  let g:EasyMotion_smartcase = 1
  imap pp <esc>a<Plug>snipMateNextOrTrigger
  smap pp <Plug>snipMateNextOrTrigger
  nnoremap 5 %
  nnoremap 1 za
  nnoremap , zO
  nnoremap ,, zC

  " Set vim as the default editor for crontab 
  if $VIM_CRONTAB == "true"
    set nobackup
    set nowritebackup
  endif

{% endhighlight %}

### Zshrc

{% highlight zsh %}
# ZSH variable setting

# Set name of the theme to load.
ZSH_THEME="powerline"

POWERLINE_DEFAULT_USER=$USER

# Use case-sensitive completion.
CASE_SENSITIVE="true"

# Update every two week
export UPDATE_ZSH_DAYS=14

# Uncomment the following line to enable command auto-correction.
ENABLE_CORRECTION="true"

# Uncomment the following line to display red dots whilst waiting for completion.
COMPLETION_WAITING_DOTS="true"

plugins=(git bundler osx rake rails ruby fasd z)

# User configuration

# Coustomize highlight in zsh
if [ "$TERM" = xterm ]; then TERM=xterm-256color; fi

# Environment variable setting {

# Path to your oh-my-zsh installation.
export ZSH=~/.oh-my-zsh

# PATH ENV
export PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"

# Set language environment
export LANG=en_US.UTF-8
export LC_CTYPE="en_US.UTF-8"

# Set Vim as default editor
export EDITOR=vim

# ssh
export SSH_KEY_PATH="~/.ssh"

source $ZSH/oh-my-zsh.sh

# cd foo with `cd` 
setopt AUTO_CD

# Set alias command shortcut {
alias gs='git status'
alias bri='brew install'
alias bru='brew update'
alias brd='brew doctor'
# for linux {
alias sai='sudo apt-get install'
alias syi='sudo yum install'
# }
alias vz='vi ~/.zshrc'
alias vm='vi ~/.vimrc'
alias bi='sudo bundle install --verbose'
alias gi='sudo gem install --verbose'
alias gtr='cd "$(git rev-parse --show-toplevel)" '
alias fuck='eval $(thefuck $(fc -ln -1 | tail -n 1)); fc -R'
alias vih='sudo vi /etc/hosts'
alias php-cli='php -a'
alias tail='tail -f'
# }

# Command highlight for zsh {
setopt extended_glob
TOKENS_FOLLOWED_BY_COMMANDS=('|' '||' ';' '&' '&&' 'sudo' 'do' 'time' 'strace')

recolor-cmd() {
region_highlight=()
colorize=true
start_pos=0
for arg in ${(z)BUFFER}; do
((start_pos+=${#BUFFER[$start_pos+1,-1]}-${#${BUFFER[$start_pos+1,-1]## #}}))
((end_pos=$start_pos+${#arg}))
if $colorize; then
colorize=false
res=$(LC_ALL=C builtin type $arg 2>/dev/null)
case $res in
  *'reserved word'*)   style="fg=magenta,bold";
  *'alias for'*)       style="fg=cyan,bold";
  *'shell builtin'*)   style="fg=yellow,bold";
  *'shell function'*)  style="fg=green,bold";
  *"$arg is"*)
  [[ $arg = 'sudo' ]] && style="fg=red,bold" || style="fg=blue,bold";
  *)                   style='none,bold';;
esac
region_highlight+=("$start_pos $end_pos $style")
fi
[[ ${${TOKENS_FOLLOWED_BY_COMMANDS[(r)${arg//|/\|}]}:+yes} = 'yes' ]] && colorize=true
start_pos=$end_pos
done
}
check-cmd-self-insert() { zle .self-insert && recolor-cmd }
check-cmd-backward-delete-char() { zle .backward-delete-char && recolor-cmd }

zle -N self-insert check-cmd-self-insert
zle -N backward-delete-char check-cmd-backward-delete-char

alias crontab="VIM_CRONTAB=true crontab"

# }


{% endhighlight %}

### What it looks
* in iterm2 (mac os x)

![alt](/img/shell.png)

* vim

![alt](/img/vim.png)
