# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "precise32"
  config.vm.box_url = "http://files.vagrantup.com/precise32.box"
  config.vm.network :private_network, ip: "192.168.8.120"
  config.vm.network :forwarded_port, guest: 80, host: 8080
  config.vm.provision :shell, :path => "vagrant-provision.sh"
  config.vm.synced_folder ".", "/vagrant", :owner => "www-data"
end
