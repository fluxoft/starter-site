Vagrant.configure("2") do |config|
  config.vm.box = "precise64"
  config.vm.provision "shell", path: "deploy/provision.sh"
  config.vm.network :forwarded_port, host: 8111, guest: 80
  config.vm.synced_folder ".", "/websites/starter.com", type: "rsync"
end
