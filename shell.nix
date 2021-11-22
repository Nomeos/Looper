{ pkgs ? import <nixpkgs> {} }:

let
    php' = pkgs.php80.buildEnv {
                extensions = { all, enabled, ... }: with all; [ session pdo ];
                extraConfig = ''
                [xdebug]
                zend_extension=${pkgs.php80Extensions.xdebug}/lib/php/extensions/xdebug.so
                        xdebug.mode=debug
                        xdebug.client_host=127.0.0.1
                        xdebug.client_port=9003'';
    };
    python' = pkgs.python3.withPackages (p: with p; [
        psutil
    ]);
in
    pkgs.mkShell {
        buildInputs = [
            python'
            php'
            php'.packages.composer
        ];
        shellHook = ''
            PYTHONPATH=${python'}/${python'.sitePackages}
        '';
    }