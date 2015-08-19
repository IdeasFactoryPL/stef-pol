ZIP=glass_v131.zip
PACK=glass.js glass.php readme.txt rim/rim* spy/spy*

squaringthecircle: squaringthecircle.c Makefile
	$(CC) squaringthecircle.c -o squaringthecircle
	./squaringthecircle

zip: $(ZIP)

$(ZIP): $(PACK) Makefile
	rm -rf glass $(ZIP)
	mkdir glass
	tar cf - $(PACK) | (cd glass; tar xf -)
	zip -9 -o -r $(ZIP) glass
	rm -rf glass
