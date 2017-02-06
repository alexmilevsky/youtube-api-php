
<?php $counter = 1; foreach($videos as $video) { ?>
<li class="search-item">
    <a href="#accordion-<?=$counter?>" class="search-item-title"><?=$video->title?></a><br />
    <?=$video->author?><br />
    <?=$video->date?><br />
    <div id="accordion-<?=$counter?>" class="search-item-content">
        <iframe width="640" height="360" src="https://www.youtube.com/embed/<?=$video->id?>" frameborder="0" allowfullscreen></iframe>
    </div>
</li>
<?php $counter++; } ?>