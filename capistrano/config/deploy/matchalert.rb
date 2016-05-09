load 'config/deploy/matchalertExclude'
load 'config/deploy/credentials'
set :user,fetch(:user,"lavesh.rawat")
set :port,'2525'
role :web,"lfvsfcp10120.dn.net" #"admin@testjs-01.infoedge.com" # "root@172.16.3.185" #,"reshu@192.168.38.16" "reshurajput@try.reshu.com" #,"192.168.38.16" 
role :app,"lfvsfcp10120.dn.net" #"admin@testjs-01.infoedge.com"# "root@172.16.3.185" #,"reshu@192.168.38.16" #"reshurajput@try.reshu.com" #,"192.168.38.16"
set :deploy_to, '/var/www/capistrano1/html'
set :copy_dir, '/var/www/capistrano1/temp'
set :remote_copy_dir, '/temp'
set :shared_children, ["/log","web/uploads","/cache","web/smarty/templates_c","web/robot_nofollow.txt","web/robots.txt","web/stats","web/profile","web/admin"]
set :current_stage,'matchalert'
set :symfony_cc_php,'/usr/local/php/bin/php'
