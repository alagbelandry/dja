set :application, "auportdadjame"
set :domain,      "ftp.#{application}.com"
set :deploy_to,   "/www/#{application}"

set :repository,  "git@github.com:alagbelandry/dja.git"
set :scm,         :git

set :model_manager, "propel"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  3

set :shared_files,      ["app/config/parameters.ini"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]
set :update_vendors, true
set :use_composer, true

set :use_sudo, false

set :deploy_via, :copy

# Use copy to bypass firewall...
set :copy_strategy, :export
set :copy_cache, "/tmp/#{application}"
set :copy_exclude, [".git/*"]
set :copy_compression, :gzip

default_run_options[:pty] = true

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL