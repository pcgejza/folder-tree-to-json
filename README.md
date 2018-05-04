# Mappák struktúráját feltérképező script

Feltérképezi a teljes mappa struktúját és az almappákat, teljesen a struktúra legmélyéig majd egy kimeneti json fájlba elmenti. 
Argumentumlista:
- mappa elérési útvonala
- kimeneti fájl elérési útvonala
- fájlok elérésének URL-je

Példa a futtatásra:
```
php stuctureToJson.php testfolder test.json 'http://users.iit.uni-miskolc.hu/~huzynets/download.php?file='
```