### Example

Create `custom_path/summernote_with_elfinder.twig`
```html
{% include('@project/modules/elfinder/extensions/summernote/summernote-ext-elfinder.twig') %}

<script>
    $(document).ready(function() {
        $('{{ selector }}').summernote({
             lang: 'ru-RU',
             toolbar: [
                ['history', ['undo', 'redo']],
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'elfinder', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
             ]
        });
    });

</script>
```

set template in wysiwyg instance
```php
$wysiwyg = \EnjoysCMS\Core\Components\WYSIWYG\WYSIWYG::getInstance('summernote', $container);
$wysiwyg->getEditor()->setTwigTemplate('custom_path/summernote_with_elfinder.twig'); 
echo $wysiwyg->selector('#description');
```
