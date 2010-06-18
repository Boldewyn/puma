dir ..\..\*.php /A:-D /B /S > files
xgettext -L php -k__ -k_e -o messages.pot -f files