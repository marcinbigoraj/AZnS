ALTER TABLE `search` ADD `anyWord` BOOLEAN NOT NULL AFTER `id_cat` ,
ADD `includeDescription` BOOLEAN NOT NULL AFTER `anyWord` 