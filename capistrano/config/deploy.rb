require 'csv'
set :stages, %w(jstest staging live matchalert)
set :default_stage, "jstest"

require 'capistrano/ext/multistage'

set :application, 'JeevansathiSvn'
set :repository, fetch(:repo,'http://svntrac.infoedge.com/svn/jeevansathi/trunk')
set :scm, :subversion
set :deploy_via, :remote_cache

    # Require subversion to do an export instead of a checkout.
set :checkout, 'export'
    # We want to max of 5 releases at a time
set  :keep_releases,   2

    # We want to deploy everything under your user, and we don’t want to use sudo
set :use_sudo, false

# Need to give cc variable as "n" if symfony cc not required
set :cc, fetch(:cc, "y")

    # We want to write svn revision info in REVISION file 
require 'capistrano/recipes/deploy/scm/subversion'    
Capistrano::Deploy::SCM::Subversion.class_eval do
  def query_revision(revision)
    return revision if revision =~ /^\d+$/
    result = yield(scm(:info, repository, authentication, "-r#{revision}"))
    YAML.load(result)['Last Changed Rev']
  end
end

    # ——————————– Final Config ——————————–
    # This configuration option for pseudo terminal is helpful when using svn+ssh but doesn’t hurt anything to leave it enabled always.
default_run_options[:pty] = true

# Custom tasks for our hosting environment.
namespace :remote do

desc <<-DESC
    Create directory required by copy_remote_dir.
  DESC
  task :create_copy_remote_dir, :roles => :app do
    print "    creating #{remote_copy_dir}.\n"
    run 'mkdir -p "/tmp"'
  end

desc <<-DESC
    Create a symlink to the application.
  DESC
  task :create_symlink, :roles => :web do
    print "    creating symlink -> #{current_path}.\n"
    run "ln -s #{current_path}"
  end


end

# Custom tasks for our local machine.
namespace :local do

desc <<-DESC
    Create directory required by copy_dir.
  DESC
  task :create_copy_dir do
    print "    creating #{copy_dir}.\n"
    system "mkdir -p #{copy_dir}"
    print "#{current_revision}"
    print "executing on local"
  end

end
# Callbacks.
after 'deploy:setup','oneTime:copyUploads'
before 'deploy:update','symfony:cleancache','commonChanges:getChangedFiles','incrementversion:updatecommonfile'
after 'deploy:update','symfony:cleancache','linkMod:changeLinkMod'
before 'deploy:rollback','symfony:cleancache'
after 'deploy:rollback','incrementversion:updatecommonfile','incrementversion:scpCommonFileFn'

namespace :jeevansathi do
 task :jsdeploy do
  transaction do
   deploy.update
  end
 end
end


namespace :commonChanges do
 task :getChangedFiles do
  transaction do
	if(releases.size >0 )
	system "touch /tmp/capistranochangelog.txt"
        system "chmod 777 /tmp/capistranochangelog.txt"
   	system "svn log --verbose -r #{current_revision}:#{revision} #{repository} > /tmp/capistranochangelog.txt"
	result=%x[php -q common_files_changed.php]
	if(result != "NA") 
		print "Common files change is:#{result}"
		puts "Do you want to Continue (y/n)?"
      		STDOUT.flush()
		userInput = STDIN.gets.chomp
      		exit if ('Y' != userInput && 'y'!= userInput)
	end
    end
  end
 end
end
#This is used to increment or add js and css files in commonfile_functions.php by calling a script generate_commonfile_functions.php
#in both the cases deploy or rollback to avoid any invalid state of file
namespace :incrementversion do
 task :scpCommonFileFn do
		upload("../web/profile/commonfile_functions.php","#{current_path}/web/profile/commonfile_functions.php")
       	end
 task :updatecommonfile do
  transaction do
    if(releases.size >0 && current_stage!="matchalert")
	system "touch /tmp/jsCssFilelog.txt"
        system "chmod 777 /tmp/jsCssFilelog.txt"
        system "svn log --verbose -r #{current_revision}:#{revision} #{repository} > /tmp/jsCssFilelog.txt"
	system "svn up ../web/profile/commonfile_functions.php"
	result =%x[php -q generate_commonfile_functions.php]
	if(result =="Commit")
	system "svn ci -m 'changes in js and css committed by capistrano' ../web/profile/commonfile_functions.php"
	elsif(result != "NA")
                print "Output of generate_commonfile_functions is :#{result}"
               puts "DO you want to Continue (y/n)?"
                STDOUT.flush()
                userInput = STDIN.gets.chomp
                exit if ('Y' != userInput && 'y'!= userInput)
        end
    end
  end
 end
end

namespace :symfony do
task :cleancache do
 if(cc =="y") 
    print "clean cache command"
 if(symfony_cc_php)
    run "cd #{current_path} && #{symfony_cc_php} symfony cc"
 else
    run "cd #{current_path} && ./symfony cc"
 end
 end
end
end

#Following task not in current use
namespace :copyfiles do
 task :configures do
  if(previous_release) 
  	transaction do
  		copy_exclude.each do |row|
   			if(row != nil && row != '**/test')
     				mypath=row.split("**")[1]
     				run "test -f #{previous_release}#{mypath} && cp -p #{previous_release}#{mypath} #{latest_release}#{mypath} || echo 'file does not exist'"
      			end
    		end
 	end
   end
 end
end


namespace :linkMod do
 task :changeLinkMod do
  run "ln -s #{latest_release}/web/profile #{latest_release}/web/P"
 if(current_stage!="matchalert")
  run "ln -s #{latest_release}/web/profile/images #{latest_release}/web/profile/I"
  run "ln -s #{latest_release}/web/profile/imagesnew #{latest_release}/web/profile/IN"
 else
  run "chmod -R 777 #{latest_release}/web/msmjs/templates_c"
  run "chmod -R 777 #{latest_release}/web/msmjs/tempCSV"
 end
 end
end

namespace :oneTime do
 task :copyUploads do
  run "mkdir #{shared_path}/cache/Minify"
  #run "cp -r /var/www/testjs4/web/uploads/* #{shared_path}/uploads"
  run "chmod -R 777  #{shared_path}/log"
  run "chmod -R 777  #{shared_path}/cache"
  run "chmod -R 777  #{shared_path}/templates_c"
  run "chmod -R 777  #{shared_path}/uploads"
 end
end 


