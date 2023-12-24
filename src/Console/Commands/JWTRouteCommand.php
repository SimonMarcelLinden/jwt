<?php

namespace SimonMarcelLinden\JWT\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
/**
 * The JWTKeyCommand class provides a console command for generating a new JWT secret key.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 *
 */
class JWTRouteCommand extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'jwt:route {action}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Activate or deactivate JWT routes';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * Generates and sets a new JWT secret key in the .env file.
	 *
	 * @return void
	 */
	public function handle() {
		$action = $this->argument('action');

		if ($action == 'activate') {
			$this->info('JWT routes activated.');
			$this->setConfigValue('enable_routes', 'true');
		} elseif ($action == 'deactivate') {
			$this->info('JWT routes deactivated.');
			$this->setConfigValue('enable_routes', 'false');
		} else {
			$this->error('Invalid action. Please use "activate" or "deactivate".');
		}
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

	private function setConfigValue($key, $value) {
		if (!$this->configFileExists()) throw new Exception("The file 'jwt.php' in the config directory was not found. Make sure the file exists in the correct directory and that the path is set correctly.", 1);

		$path = base_path('config/jwt.php');

		if (file_exists($path)) {
			$configContent = file_get_contents($path);
			$pattern = "/('".$key."' => )([^\,]*)/";
			$replacement = "'".$key."' => ".$value;
			$configContent = preg_replace($pattern, $replacement, $configContent);
			file_put_contents($path, $configContent);
		}
	}
}
