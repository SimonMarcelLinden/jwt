<?php

namespace SimonMarcelLinden\JWT\Console\Commands;

use Exception;
use Illuminate\Console\Command;

/**
 * The JWTInstallCommand class provides a console command for setting up JWT authentication.
 * It handles copying configuration files and inserting necessary JWT configurations.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 *
 */
class JWTInstallCommand extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
    protected $signature 	= 'jwt:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Installs the JWT authentication configuration.';

	/**
     * Execute the console command.
     *
     * Handles the installation process of the JWT configuration.
     *
     * @return void
     */
	public function handle() {
		if (!$this->configFileExists()) {
			$this->info('Copy config file...');
			if ($this->publishJWTConfig()) {
				$this->info('Configuration copied successfully.');
			} else {
				$this->error('Configuration could not be copied.');
			}
		} else {
			$this->warn("Duplicate 'jwt.php' found in config directory. This may lead to conflicts. Verify or remove it before adding a new configuration.", 1);
		}

		$this->info('Insert JWT configuration...');
		$this->insertJwtConfiguration();
    }

	/**
     * Check if a file exists.
     *
     * @param string $file Path to the file.
     * @return bool
     */
	private function fileExist(string $file): bool {
		return file_exists($file);
	}

	/**
     * Check if the JWT config file exists.
     *
     * @return bool
     */
	private function configFileExists(): bool {
		return $this->fileExist(base_path('config/jwt.php'));
	}

    /**
     * Check if the bootstrap file exists.
     *
     * @return bool
     */
	private function bootstrapFileExists():bool {
		return $this->fileExist(base_path('bootstrap/app.php'));
	}

	/**
     * Publish the JWT configuration file.
     *
     * @return bool
     */
	private function publishJWTConfig () {
		return copy(__DIR__ . '/../../../config/config.php', base_path('config/jwt.php'));
	}

	/**
     * Insert JWT configuration into the bootstrap file.
     *
     * @return void
     */
	private function insertJwtConfiguration () {
		try {
			if (!$this->bootstrapFileExists()) throw new Exception("The file 'app.php' in the bootstrap directory was not found. This file is required to initialize the application. Make sure the file exists in the correct directory and that the path is set correctly.", 1);

			$file = base_path('bootstrap/app.php');
			$content = file_get_contents($file);

			$searchForJwt = "\$app->configure('jwt');";
			$searchForApp = "\$app->configure('app');";
			$insertPoint = strpos($content, $searchForApp);
			$insertContent = "\$app->configure('jwt');";

			if (strpos($content, $searchForJwt) === false) {
				if ($insertPoint !== false) {
					$content = substr_replace($content, "\n" . $insertContent, $insertPoint + strlen($searchForApp), 0);
				} else {
					if (($middlewarePos = strpos($content, '// $app->middleware(')) !== false) {
						$content = substr_replace($content, $insertContent . "\n", $middlewarePos, 0);
					} else if(($middlewarePos = strpos($content, '$app->middleware(')) !== false) {
						$content = substr_replace($content, $insertContent . "\n", $middlewarePos, 0);
					} else {
						$content .= "\n" . $insertContent;
					}
				}
				file_put_contents($file, $content);$this->info("JWT configuration successfully inserted into 'app.php'.");
			} else {
				$this->warn("JWT configuration already exists in 'app.php'.");
			}
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}

	}
}
