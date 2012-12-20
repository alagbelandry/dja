set :application, "auportdadjame"
set :domain,      "ftp.cluster006.ovh.net"
set :deploy_to,   "www/"
set :app_path,    "app"
set :web_path,    "web"
set :branch, "master"

set :repository,  "git@ssh.github.com:alagbelandry/dja.git"
set :scm,         :git

set :model_manager, "propel"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  3

set :php_bin, "/usr/local/bin/php.ORIG.5_4 -c /usr/local/lib/php.ini-2 -v"

set :shared_files,      ["app/config/parameters.ini"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]
set :use_composer, true
set :dump_assetic_assets, true

set :user, "auportda"
set :use_sudo, false


set :deploy_via, :copy

# Use copy to bypass firewall...
set :copy_strategy, :export
set :copy_cache, "/tmp/#{application}"
set :copy_exclude, [".git/*"]
set :copy_compression, :gzip

set :symfony_env_prod, "staging"
set :writable_dirs,     ["app/cache", "app/logs"]

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL