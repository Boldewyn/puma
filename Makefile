#
# Makefile for Puma.Phi
#
YUI = java -jar C:/cygwin/home/Manuel/lib/yuicompressor-2.4.2/build/yuicompressor-2.4.2.jar
LOCALEDIR =  aigaionengine/language/locale
USAGE = "Targets:\n\
all:    zip and minify static components\n\
export: puma send -av\n\
clean\n\
i18n:   create message catalog\n\
i18nc:  compile messages"

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

i18n:
	xgettext --from-code=UTF-8 -L php -k__ -k_e -o $(LOCALEDIR)/messages.pot `find aigaionengine -name '*.php'`
	msgmerge $(LOCALEDIR)/de/LC_MESSAGES/messages.po $(LOCALEDIR)/messages.pot > $(LOCALEDIR)/de/LC_MESSAGES/messages.po.new
	mv $(LOCALEDIR)/de/LC_MESSAGES/messages.po.new $(LOCALEDIR)/de/LC_MESSAGES/messages.po

i18nc:
	msgfmt -v -o $(LOCALEDIR)/de/LC_MESSAGES/messages.mo $(LOCALEDIR)/de/LC_MESSAGES/messages.po
	touch $(LOCALEDIR)/de/LC_MESSAGES/messages.mo

help:
	@echo -e $(USAGE)
