Vagrant.configure("2") do |config|
  config.vm.box = "precise64"
  config.vm.provision :puppet
  config.vm.network :forwarded_port, host: 8111, guest: 80
  config.vm.synced_folder ".", "/var/www", type: "rsync"
end
