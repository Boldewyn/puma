#
# Makefile for Puma.Phi
#
YUI = java -jar C:/cygwin/home/Manuel/lib/yuicompressor-2.4.2/build/yuicompressor-2.4.2.jar

all: static/js/jquery.js static/js/puma.js static/css/style.css
	$(YUI) --type css --charset utf-8 -o static/css/style.min.css static/css/style.css
	gzip -c static/css/style.min.css > static/css/style.css.gz
	rm -f static/css/style.min.css
	gzip -c static/js/jquery.js > static/js/jquery.js.gz
	gzip -c static/js/puma.js > static/js/puma.js.gz

export:
	puma send -av

.PHONY: clean

clean:
	rm -f static/css/style.min.css
	rm -f static/css/style.css.gz
	rm -f static/js/*.gz
