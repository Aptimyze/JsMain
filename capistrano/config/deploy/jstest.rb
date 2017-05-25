load 'config/deploy/jstestExclude'
load 'config/deploy/credentials'
set :user,fetch(:user,"admin")
role :web, "testjs-05.infoedge.com" #"root@172.16.3.185" #,"reshu@192.168.38.16" "reshurajput@try.reshu.com" #,"192.168.38.16" 
role :app, "testjs-05.infoedge.com"  #"root@172.16.3.185" #,"reshu@192.168.38.16" #"reshurajput@try.reshu.com" #,"192.168.38.16"
set :deploy_to, fetch(:deployDir, "/var/www/capistrano/testjs3/")
set :copy_dir, '/tmp'
set :remote_copy_dir, '/tmp'
set :shared_children, ["/log","web/uploads","/cache","web/smarty/templates_c","web/robot_nofollow.txt","web/robots.txt","web/sitemap_index.xml","web/sitemap_index_daily.xml","web/sitemap_index_mobile.xml","web/sitemap_index_mobile_daily.xml","web/xmldir","web/stats"]
set :current_stage,'jstest'
