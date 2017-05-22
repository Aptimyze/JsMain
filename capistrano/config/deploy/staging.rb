load 'config/deploy/applicationExclude'
load 'config/deploy/credentials'
set :user,fetch(:user,"reshurajput")
role :web, "192.168.38.42","172.16.3.185" #,"reshu@192.168.38.16" "reshurajput@try.reshu.com" #,"192.168.38.16" 
role :app, "192.168.38.42","172.16.3.185" #,"reshu@192.168.38.16" "reshurajput@try.reshu.com" #,"192.168.38.16" 
set :deploy_to, '/var/www/html/capistrano/trunk' #'/var/www/html/capistrano/jsApp'
set :copy_dir,'/var/www/html/capistrano/tmp' # '/home/reshu/tmp'
set :remote_copy_dir, '/var/www/html/capistrano/tmp'
set :shared_children, ["/log","web/uploads","/cache","web/smarty/templates_c","web/robot_nofollow.txt","web/robots.txt","web/sitemap_index.xml","web/sitemap_index_daily.xml","web/sitemap_index_mobile.xml","web/sitemap_index_mobile_daily.xml","web/xmldir","web/stats"]
set :current_stage,'live'
set :symfony_cc_php,false

