$tagmaker = new model($database, $config, 'tag');
$sth = $database->dbh->prepare("	
	select
		content_id
		, tag_id
	from content_tag
");
$sth->execute();				
foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
	$rows[$row['tag_id']][] = $row;
}
foreach ($rows as $newtagname => $temp) {
	$tagmaker->create(array(
		'title' => $newtagname
	));
	$rowspairedwithnewid[$newtagname] = $tagmaker->getLastInsertId();
}
$tagmaker->setIdentity('content_meta');
foreach ($rows as $taggroup) {
	foreach ($taggroup as $contandtag) {
		$tagmaker->create(array(
			'name' => 'tag'
			, 'content_id' => $contandtag['content_id']
			, 'value' => (array_key_exists($contandtag['tag_id'], $rowspairedwithnewid) ? $rowspairedwithnewid[$contandtag['tag_id']] : '')
		));
	}
}

echo '<pre>';
print_r($rowspairedwithnewid);
print_r($rows);
echo '</pre>';
exit;