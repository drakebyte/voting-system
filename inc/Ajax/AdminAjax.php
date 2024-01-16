<?php

namespace CodingTest\Ajax;

abstract class AdminAjax {
	protected array $output = [
		'success' => false,
		'status'  => 400,
		'error'   => '',
		'message' => "This ajax did nothing",
		'html'    => "This ajax did <strong>nothing</strong>",
	];

	public function __construct() {
		add_action( 'wp_ajax_' . $this->set_hook_name(), [ $this, 'handle_ajax_request' ] );
		add_action( 'wp_ajax_nopriv_' . $this->set_hook_name(), [ $this, 'handle_ajax_request' ] );
		$this->add_construct();
	}

	abstract public function set_hook_name(): string;

	abstract public function add_construct(): void;

	public function handle_ajax_request(): void {
		$this->output = array_merge( $this->output, $this->create_output() );
		$this->output_json();
	}

	abstract public function create_output(): array;

	public function output_json(): void {
		wp_send_json( $this->output, $this->output['status'] );
	}
}
