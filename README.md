# Paginator

Very small PHP class to generate pagination links.

## Usage

In your controller/route:

    <?php
    $offset      = intval($_GET['offset']);
    $per_page    = 20;
    $total_items = 60;
    $query_items = array('something' => 'to_persist_on_paginator_links');
    
    $paginator   = new Paginator\Paginator('/example/url', $offset, $per_page, $total_items, $query_items);
    ?>

In your view or wherever you want the links rendered:

    <?php echo $paginator->links(); ?>

## Localization (and dependency)

The class uses the function `__()` when rendering the links. If you are not using a framework
that already defines that function, you can define it as follows:

    function __($key)
    {
        $translations = array(
            'pagination.first'    => 'First',
            'pagination.previous' => 'Previous',
            'pagination.next'     => 'Next',
            'pagination.last'     => 'Last',
        );
        
        return array_key_exists($key, $translations) ? $translations[$key] : $key;
    }

I understand that adding a makedo function like that may not seem optimal, but if you are ever going
to localize your application, you'll be happy your Paginator class doesn't need any monkeypatching!

## Example of generated mark-up

    <div class="pagination pagination-centered">
    	<ul>
    		<li><a href="/?offset=0">Previous</a></li>
    		<li><a href="/?offset=0">1</a></li>
    		<li class="active"><a href="/?offset=20">2</a></li>
    		<li><a href="/?offset=40">3</a></li>
    		<li><a href="/?offset=60">Next</a></li>
    	</ul>
    </div><!-- Bootstrap-ready :D -->

### License

Paginator is released under the [MIT license](http://www.opensource.org/licenses/MIT).
