{ pkgs ? import <nixpkgs> {} }:

let
    python = pkgs.python3;
    php-packages = pkgs.php80Packages;
    python-with-packages = python.withPackages (p: with p; [
        psutil
    ]);
in
    pkgs.mkShell {
        buildInputs = [
            python-with-packages
            php-packages.composer
        ];
        shellHook = ''
            PYTHONPATH=${python-with-packages}/${python-with-packages.sitePackages}
        '';
    }