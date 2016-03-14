# Set the PS1 prompt (with colors)
export PS1="\[\033[01;33m\]\u@\h\[\033[01;34m\] \w \$\[\033[00m\] "

export PAGER="less -M +Gg"

# Avoid succesive duplicates in the bash command history.
export HISTCONTROL=ignoredups

# Append commands to the bash command history file (~/.bash_history) instead of overwriting it.
shopt -s histappend

# Append commands to the history every time a prompt is shown, instead of after closing the session.
PROMPT_COMMAND='history -a'

# Add some easy shortcuts for formatted directory listings and add a touch of color.
alias ll='ls -alF --color=auto'

# Make grep more user friendly by highlighting matches
alias grep='grep --color=auto'

# fix clear
export TERM='xterm-256color'

cd /var/www/project
