<?php

return [
    'adminEmail' => 'buzzr@inbox.ru',
	'robotEmail' => 'robot@buzzr.ru',
	'resizes' => [
			[
				'prefix' => 'full',
				'command' => '-resize 1000x1000',
			],
			[
				'prefix' => 'banner',
				'command' => '-thumbnail 930x350^ -extent 930x350 -gravity center',
			],
			[
				'prefix' => 'middle',
				'command' => '-resize 300x300',
			],
			[
				'prefix' => 'preview',
				'command' => '-resize 200x200',
			],
			[
				'prefix' => 'small',
				'command' => '-resize 260x100',
			],
			
		]
];
