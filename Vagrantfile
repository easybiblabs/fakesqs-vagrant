box_url  = 'https://s3.amazonaws.com/easybibdeployment/imagineeasy-ubuntu-14.04.3_virtualbox-4.3.26r98988_chef-11.10.4_1.box'
box_file = 'imagineeasy-ubuntu-14.04.3_virtualbox-4.3.26r98988_chef-11.10.4_1'

web_host_ip = '33.33.33.42'

v = Bib::Vagrant
unless v.respond_to?('check_plugins')
  puts 'This needs an up to date version of bib-vagrant'
  puts 'please execute vagrant plugin update bib-vagrant'
  exit 1
end

Vagrant.configure('2') do |config|
  bibconfig = Bib::Vagrant::Config.new
  vagrantconfig = bibconfig.get

  if Vagrant.has_plugin?('vagrant-logs')
    config.vagrant_logs.log_files = ['/var/log/syslog', '/var/log/nginx/*.log', '/var/log/php/*.log']
  end

  dna = JSON.parse(File.read("#{File.expand_path(File.dirname(__FILE__))}/web-dna.json"))

  config.vm.define :web do |web_config|
    web_config.vm.box     = box_file
    web_config.vm.box_url = box_url

    web_config.vm.hostname = 'fakesqs.bib'
    web_config.vm.network :private_network, ip: web_host_ip

    web_config.vm.provider :virtualbox do |vb|
      vb.gui = vagrantconfig['gui']

      vb.customize [
        'modifyvm', :id,
        '--name', 'FakeSQS Vagrant POC'
      ]
    end

    # uncomment the next line and re-run provision if you end up with a
    # "Failed to fetch mirror://mirrors.ubuntu.com/mirrors.txt" error:
    # web_config.vm.provision 'shell', inline: 'apt-spy2 fix --commit; apt-get update -y'
    web_config.vm.provision "shell", inline: "apt-spy2 fix --commit --launchpad --country=US; apt-get update -y"

    Bib::Vagrant.default_provision(web_config)
    Bib::Vagrant.init_github_hostkey(web_config)
    Bib::Vagrant.check_gatling if vagrantconfig['rsync']

    host_folder = "#{File.dirname(__FILE__)}/www"
  
    web_config.vm.provision :chef_solo do |chef|
      chef.cookbooks_path = vagrantconfig['cookbook_path']
      chef.log_level = vagrantconfig['chef_log_level']
      chef.add_recipe 'ies::role-phpapp'
      # chef.add_recipe 'php-fpm::service'
      # chef.add_recipe 'nginx-app::service'
      chef.add_recipe 'nginx-app::vagrant-silex'
      chef.add_recipe 'fakesqs'
      chef.json = Bib::Vagrant.prepare_app_settings(vagrantconfig, web_config, dna, host_folder)
    end

    Bib::Vagrant.init_github_hostkey(web_config)
    Bib::Vagrant.check_gatling if vagrantconfig['rsync']

    web_config.vm.provision "shell", inline: "cd /vagarant_www && php ./composer.phar install"

  end
end

plugins = { 'vagrant-hosts' => false,
            'vagrant-faster' => false,
            'vagrant-cachier' => true,
            'bib-vagrant' => true,
            'vagrant-logs' => false
}
exit 1 unless Bib::Vagrant.check_plugins(plugins)
