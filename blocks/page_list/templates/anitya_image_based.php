<?php 
defined('C5_EXECUTE') or die("Access Denied.");

// Some settings for this template :

	$topicAttributeKeyHandle = "project_topics";
	$tagAttributeHandle = "tags";

// Some internal variables

	$rssUrl = $showRss ? $controller->getRssUrl($b) : '';
	$th = Loader::helper('text');
	$dh = Core::make('helper/date');
    $type = \Concrete\Core\File\Image\Thumbnail\Type\Type::getByHandle('tiny');

?><div class="page-list img-based run-wrap masonry">

    <?php  if ($pageListTitle): ?>
    <div class="page-list-header">
        <h3><?php  echo $pageListTitle?></h3>
    </div>
    <?php  endif; ?>
	<div class="glued-3 grid-sizer"></div>
	<?php  foreach ($pages as $key => $page):

		$pair = $key % 2 == 1 ? 'pair' : 'impair';

		$title = $th->entities($page->getCollectionName());
		$url = $nh->getLinkToCollection($page);
        $date = date('M / d / Y',strtotime($page->getCollectionDatePublic())); 

		$target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
		$target = empty($target) ? '_self' : $target;

		$description = $page->getCollectionDescription();
		$description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
		$description = $th->entities($description);	

		$topics = $page->getAttribute($topicAttributeKeyHandle);
        $options = $page->getAttribute($tagAttributeHandle);
        $options = is_object($options)  ? $options->getOptions() : array();

        if ($displayThumbnail) :
	        $img_att = $page->getAttribute('thumbnail');
	        if($type != NULL && is_object($img_att)) :
	            $thumbnailUrl = $img_att->getThumbnailURL($type->getBaseVersion());
	        else: 
	        	$thumbnailUrl = false;
	        endif;	
	    endif;
		
?>
		<div class="item <?php  echo $pair ?> glued-3 detect run2">
			<a href="<?php  echo $url ?>">
				<?php  if ($thumbnailUrl) : ?>
				<img src="<?php echo $thumbnailUrl ?>" alt="<?php echo $title ?>">
				<?php  endif ?>
				<div class="info">
					<?php  if ($includeName): ?><h2><?php  echo $title ?></h2><?php  endif ?>
					<?php  if ($includeDescription): ?><p><?php  echo $description ?></p><?php  endif ?>
					<?php  if (is_array($topics)): ?><p class="topics"><i><small><?php  foreach ($topics as $key => $topic) : ?><?php  echo $topic->getTreeNodeDisplayName() ?><?php  endforeach ?></small></i></p><?php  endif ?>				
					<?php  if ($includeDate) : ?><small><?php  echo $date ?></small><?php  endif ?>
				</div>
			</a>
		</div>
	<?php  endforeach; ?>

    <?php  if (count($pages) == 0): ?>
        <div class="ccm-block-page-list-no-pages"><?php  echo $noResultsMessage?></div>
    <?php  endif;?>
 

	<?php  if ($showRss): ?>
		<div class="ccm-block-page-list-rss-icon">
			<a href="<?php  echo $rssUrl ?>" target="_blank"><img src="<?php  echo $rssIconSrc ?>" width="14" height="14" alt="<?php  echo t('RSS Icon') ?>" title="<?php  echo t('RSS Feed') ?>" /></a>
		</div>
		<link href="<?php  echo BASE_URL.$rssUrl ?>" rel="alternate" type="application/rss+xml" title="<?php  echo $rssTitle; ?>" />
	<?php  endif; ?>
 
</div><!-- end .ccm-block-page-list -->


<?php  if ($showPagination): ?>
    <?php  echo $pagination;?>
<?php  endif; ?>