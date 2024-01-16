<?php

namespace CodingTest\Settings;

class EnqueueAssets {
	private array $assets = [];

	public function __construct() {
		$this->assets = [
			'css' => [
				'site' => 'styles-site.min.css',
			],
			'js'  => [
				'site' => 'scripts-site.min.js',
			],
		];

		add_action( 'wp_enqueue_scripts', [ $this, 'assets_site' ], 99 );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts_site' ], 99 );
	}

	public function assets_site(): void {
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'wordpress-plugin-coding-test-site', $this->get_asset( 'css', 'site' ), [], CODINGTEST_PLUGIN_VERSION );
	}

	private function get_asset( string $type, string $asset ): string {
		if ( ! $type || ! $asset ) {
			return '';
		}
		$path = CODINGTEST_PLUGIN_URL . '/assets/' . $type . '/';

		return $path . $this->assets[ $type ][ $asset ];
	}

	public function scripts_site(): void {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wordpress-plugin-coding-test-site', $this->get_asset( 'js', 'site' ), [ 'jquery' ], CODINGTEST_PLUGIN_VERSION, true );

		wp_script_add_data( 'wordpress-plugin-coding-test-site', 'strategy', 'defer' );
		wp_localize_script( 'wordpress-plugin-coding-test-site', 'wordpressplugincodingtest',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			]
		);
	}
}
