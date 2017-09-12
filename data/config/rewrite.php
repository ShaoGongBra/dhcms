<?php
return array(
    /* URL规则 */
    'REWRITE_RULE' =>array(
        'list-<class_id>.html' => 'article/Category/index',
        'page-<class_id>.html' => 'page/Category/index',
        'article/<content_id>.html' => 'article/Content/index',
        'form-<name>/<id>.html' => 'dhcms/Form/info',
        'form-<name>.html' => 'dhcms/Form/index',
        'tags-list.html' => 'dhcms/Tags/index',
        'tags/<name>.html' => 'dhcms/TagsContent/index',
        '<class_urlname>/select/<info>.html' => 'article/Category/filter',
    ),
);