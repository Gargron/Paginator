# Paginator

Very small PHP class to generate pagination links.

## Usage

In your controller/route:

    <?php

    Paginator\Paginator::$offset = intval($_GET['offset']); // intval() is only an example. Default: 0
    Paginator\Paginator::$total  = 60;       // Query result to count all items in the database, perhaps? Default: 1
    Paginator\Paginator::$limit  = 20;       // optional, 20 by default
    Paginator\Paginator::$url    = "/posts"; // optional, / by default
    ?>

In your view or wherever you want the links rendered:

    <?php echo Paginator\Paginator::links(); ?>

## Example of generated mark-up

    <nav class="paginator">
    	<ul>
    		<li><a href="/?offset=0">Previous</a></li>
    		<li><a href="/?offset=0">1</a></li>
    		<li class="current"><a href="/?offset=20">2</a></li>
    		<li><a href="/?offset=40">3</a></li>
    		<li><a href="/?offset=60">Next</a></li>
    	</ul>
    </nav>