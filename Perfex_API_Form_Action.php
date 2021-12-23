<?php

    class Perfex_API_Form_Action extends \ElementorPro\Modules\Forms\Classes\Action_Base {
        public function get_name() {return 'PerfexAPI';}

        public function get_label() {return __( 'PerfexAPI', 'text-domain' );}

        /**
         * register elementor forms
         * @param type $widget 
         * @return type
         */
        public function register_settings_section( $widget ) {

            $widget->start_controls_section(
                'section_perfex_api',
                [
                    'label' => __( 'PerfexAPI', 'text-domain' ),
                    'condition' => [
                        'submit_actions' => $this->get_name(),
                    ],
                ]
            );

            $widget->add_control(
                'perfex_endpoit_url',
                [
                    'label' => __( 'End Poist Url *', 'text-domain' ),
                    'type' => \Elementor\Controls_Manager::URL,
                    'placeholder' => 'http://yourmauticurl.com/',
                    'label_block' => true,
                    'separator' => 'before',
                    'description' => __( 'Enter the ENDPOINT where you have API_Perfex installed', 'text-domain' ),
                ]
            );


            $widget->add_control(
                'authtoken',
                [
                    'label' => __('Token *', 'text-domain'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => '99',
                    'label_block' => true,
                    'separator' => 'before',
                    'description' => __( 'Fill with your token API', 'text-domain' ),
                ]
            );


            $widget->end_controls_section();
        }


        public function run( $record, $ajax_handler ) {
            $settings = $record->get( 'form_settings' );

            //  Make sure that there is a Mautic url
            if ( empty( $settings['perfex_endpoit_url'] ) ) {
                return;
            }

            // Get sumitetd Form data
            $raw_fields = $record->get( 'fields' );
			 // Normalize the Form Data
            $fields = [];
			
            foreach ( $raw_fields as $id => $field ) {
                $fields[ $id ] = $field['value'];
            }

			// Caso queira add mais fields, e so colocar ai
// 			$fields = array("company"=>"OLA TEST OK", "phonenumber"=>"8596859001");
// 			
			//Transforma os dados no Formato -> multipart/form-data aaaaaaaahhhhhh que chato do carai!!!!
			$boundary = uniqid();
			$delimiter = '-------------' . $boundary;
			$data = '';
			$eol = "\r\n";
			foreach ($fields as $name => $content) {
				$data .= "--" . $delimiter . $eol
					. 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
					. $content . $eol;
			}
			$data .= "--" . $delimiter . "--".$eol;

			$post_data = $data;
			//FIM do Transforma os dados no Formato -> multipart/form-data aaaaaaaahhhhhh que chato do carai!!!!
			
			//Inicio do Envio dos dados transformados!
			$url = rtrim($settings['perfex_endpoit_url']['url']);
			$authtoken = $settings['authtoken'];
			$options = [
				'body'        => $post_data,
				'headers'     => [
					'Content-Type' => 'multipart/form-data; boundary=' . $delimiter,
					'authtoken' => $authtoken
				],
				'timeout'     => 30,
				'redirection' => 5,
				'blocking'    => true,
				'httpversion' => '1.0',
				'sslverify'   => false,
				'data_format' => 'body',
			];
			$resp = wp_remote_post( $url, $options );
			$body = wp_remote_retrieve_body( $resp );
			$response_code = wp_remote_retrieve_response_code( $resp );
			//Fim do Envio dos dados transformados!

			//=====================> Start TESTE <======================================
			$endpoint = "https://atendimento1.uatiz.app/uatizs/send-message";
			$TOKEN=						"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZF93cCI6IjEiLCJlbWFpbF93cCI6InkubS5saW1hMTlAZ21haWwuY29tIiwiaWF0IjoxNjM5NjIyODE2LCJleHAiOjE2NDQ4MDY4MTZ9.y5xFGQhYFcDtvWdXFi1LDUUHTLOJGJGvk8UHDrbpX0I";
			
		$message = $body;
// 		$message = wp_json_encode( $body );
		$body = [
			'number' => '558596286998',
			'message' => $message
		];

		$body = wp_json_encode( $body );

		$options = [
			'body'        => $body,
			'headers'     => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $TOKEN
			],
			'timeout'     => 30,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];
		wp_remote_post( $endpoint, $options );
		//=====================> End TESTE <======================================


            // $message = preg_match('/<div class=\"well text-center\">(.*?)<\/div>/s', $response['body'], $match);




            // echo trim(strip_tags($match[1]));

        }
        public function on_export( $element ) {
            return $element;
        }
    }