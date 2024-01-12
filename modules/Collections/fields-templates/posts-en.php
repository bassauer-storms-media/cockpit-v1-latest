<?php
/**
 * This file is part of the Cockpit project.
 *
 * (c) Artur Heinze - 🅰🅶🅴🅽🆃🅴🅹🅾, http://agentejo.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'name'   => 'posts',
    'label'  => 'Posts (EN)',
    'fields' => [
        [
            'name'     => 'published',
            'label'    => 'Published',
            'type'        => 'boolean',
            'default'     => '',
            'info'        => '',
            'localize'    => false,
            'options'     => [
                'default' => false,
                'label'   => false
            ],
            'width'       => '1-4',
            'lst'         => true
        ],
        [
            'name'     => 'title',
            'label'    => 'Title',
            'type'     => 'text',
            'default'  => '',
            'info'     => '',
            'localize' => false,
            'options'  => [
                'slug' => true
            ],
            'width'    => '3-4',
            'lst'      => true,
            'required' => true
        ],
        [
            'name'     => 'image',
            'label'    => 'Featured Image',
            'type'     => 'asset',
            'default'  => '',
            'info'     => '',
            'localize' => false,
            'options'  => [],
            'width'    => '1-1',
            'lst'      => true
        ],
        [
            'name'     => 'excerpt',
            'label'    => 'Excerpt',
            'type'     => 'textarea',
            'default'  => '',
            'info'     => '',
            'localize' => false,
            'options'  => [],
            'width'    => '1-1',
            'lst'      => true
        ],
        [
            'name'     => 'text',
            'label'    => 'Text',
            'type'     => 'wysiwyg',
            'default'  => '',
            'info'     => '',
            'localize' => false,
            'options'  => [],
            'width'    => '1-1',
            'lst'      => true
        ],
    ]
];
