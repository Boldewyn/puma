<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-faq" class="help-content regular-text">
  <h2><?php _e('Frequently Asked Questions')?></h2>
  <p><?php printf(__('This section tries to clarify some of the most common '.
     'questions about %s. If you have a question that isn’t answered here, '.
     'please use the form at the bottom of this site to contact the admin.'), 
     site_title())?></p>
  <script type="text/javascript">
    function faq_show(n) { $('dd').eq(n).show('def'); };
    function faq_hide(n) { $('dd').eq(n).hide('def'); };
    function faq_show_all() { $('dd').show(); }
    function faq_hide_all() { $('dd').hide(); }
    $(faq_hide_all);
    $(function () { $('dt').click(function () {
        var dd = $(this).nextUntil('dt');
        if (dd.css('display') == 'none') {
            dd.show('def');
        } else {
            dd.hide('def');
        }
    }).css({
        'font-style': 'italic',
        'margin-top': '.5em',
        'cursor': 'pointer'
    });
    $('dd').css({
        'padding-left': '1em'
    });
    });
  </script>
  <p>
    <a href="#" onclick="faq_show_all();return false;"><?php _e('Show all answers') ?></a>
  </p>
  <div class="section">
    <h3>Allgemein</h3>
    <dl class="faqlist">
      <dt>Was ist <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr>?</dt>
      <dd>
        <p>
          <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> ist das <strong>Pu</strong>blication <strong>Ma</strong>nagement System der Fakultät <strong>Phy</strong>sik an
          der Universität Regensburg. Es ist eine webbasierte Datenbank zur Verwaltung der eigenen Bibliografiesammlung und zum
          Austausch von Publikationsinformationen mit anderen Forschern der Fakultät.
        </p>
      </dd>
      <dt>Was kann ich damit machen?</dt>
      <dd>
        Ein erster Einstieg ist, vorhandene BiBTeX-Dateien zu importieren. Dann kannst du deine bisherigen sowie weitere Zitate,
        Referenzen und Publikationen dort speichern, verwalten und mit anderen teilen. Du kannst außerdem mit einem einzigen
        Eingabefeld in verschiedenen Datenbanken suchen, Publikationslisten exportieren, Notizen und Kommentare komfortabel
        erstellen uvm.
      </dd>
      <dt>Wo finde ich <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr>?</dt>
      <dd>
        Die Webseite ist unter der URL &lt;<a href="http://puma.uni-regensburg.de/">http://puma.uni-regensburg.de</a>&gt; zu erreichen.
      </dd>
      <dt>Wo kann ich Fragen stellen?</dt>
      <dd>
        Es gibt eine <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr>-Mailingliste. Einfach eine kurze eMail an <a href="mailto:manuel.strehl@physik.uni-r.de">manuel.strehl@physik.uni-r.de</a>
        und du wirst eingetragen. Ansonsten: Siehe nächste Frage.
      </dd>
      <dt>Wer steht hinter <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr>?</dt>
      <dd>
        <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> ist das Promotionsprojekt von Manuel Strehl (Dipl.-Physiker) am Lehrstuhl für Medieninformatik (Prof. Wolff).
        Ein herzlicher Dank für die Unterstützung geht an dieser Stelle an die Dekanin Prof. Grifoni, ihren Vorgänger im Amt Prof. Back, Fritz Wünsch, Gernot Deinzer
        und Matthias Böhm.<br/>
        Ziel des Projekts ist eine (hoffentlich) messbare Verbesserung der Forschung in der Fakultät Physik durch Einsatz von Web 2.0-Techniken.<br/>
        Du kannst Manuel jederzeit unter <a href="mailto:manuel.strehl@physik.uni-r.de">manuel.strehl@physik.uni-r.de</a> oder donnerstags 
        und freitags im Großraumbüro (5.1.34, über dem Haupteingang Physik) oder dem Physik-CIP-Pool erreichen.
      </dd>
      <dt>Wie bekomme ich ein Benutzer-Konto?</dt>
      <dd>
        Wenn du einen Physik-Account hast (deine Email-Adresse hat ein &bdquo;@physik&ldquo;), kannst du dich einfach mit deinen normalen NDS-Zugangsdaten anmelden. Das Anmelden ist sicher und wird ausschließlich über das Portal des Rechenzentrums abgewickelt.<br/>
        Wenn du keinen Physik-Account hast, schreibe eine <a href="mailto:manuel.strehl@physik.uni-r.de?subject=Gastkonto">kurze Email an manuel.strehl@physik.uni-r.de</a>, dann erhältst du ein Gastkonto.
      </dd>
      <dt>Woher kommt <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr>? Was ist Aigaion?</dt>
      <dd>
        Aigaion ist die Software, auf der <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> basiert. Es ist unter der GPL veröffentlicht und hat unter 
        <a href="http://www.aigaion.nl/">aigaion.nl</a> seine Webpräsenz. <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> erweitert Aigaion um Features, die speziell für die
        Physik-Fakultät entwickelt werden.
      </dd>
      <dt>Wie sieht es mit Datenschutz aus?</dt>
      <dd>
        Die Frage teilt sich in zwei Bereiche:
        <ol>
          <li>
            <em>Meine Publikationen:</em> <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> bietet die Möglichkeit, für jede Publikation separat Zugriffsrechte zu vergeben und
            damit die Veröffentlichung für bestimmte oder alle Nutzer unsichtbar zu machen. Gleiches gilt für Notizen, Kommentare und
            Anhänge.
          </li>
          <li>
            <em>Meine Zugangs- und persönlichen Daten:</em> Die Daten werden derzeit in einer Datenbank des Lehrstuhls für Informationswissenschaften gespeichert.
            Neben Manuel hat somit prinzipiell der Admin des Servers darauf Zugriff. Soweit wir das
            zusichern können, werden die Daten lediglich für <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> verwendet. Sollten Umfragen oder
            Kontakte zu einzelnen Nutzern vorgesehen sein, wird die kanonische (@physik...) eMail-Adresse verwendet und nicht die, die
            im Profil angegeben wurde.<br/>
            Die Daten werden zu Forschungszwecken verwendet. Eine Weitergabe an Dritte erfolgt keinesfalls! Veröffentlichte Daten
            (ausschließlich zur Publikation von Forschungsergebnissen) werden anonymisiert.
          </li>
        </ol>
      </dd>
      <dt>Kann ich <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> verändern oder mithelfen?</dt>
      <dd>
        Sehr gerne! Da <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> für alle Physiker in Regensburg eine sinnvolle Ergänzung der täglichen Arbeit sein soll, ist jedes Feedback
        willkommen. Wenn du PHP kannst, kannst du dich gerne auch an neuen Features beteiligen.
      </dd>
      <dt>Ich habe einen Fehler in <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> gefunden. Was nun?</dt>
      <dd>
        Obwohl dies hoffentlich nicht allzu oft vorkommt, schreib bitte den Fehler sowie die Umstände, unter denen er auftrat, an das
        <a href="mailto:manuel.strehl@physik.uni-r.de"><abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr>-Team</a>. Wir kümmern uns schnellstmöglich darum. (Und das ist keine leere Phrase!)
      </dd>
    </dl>
  </div>
  <div class="section">
    <h3>Veröffentlichungen verwalten</h3>
    <dl class="faqlist">
      <dt>Wenn ich BiBTeX exportiere und in LaTeX verwende, werden Umlaute nicht richtig angezeigt.</dt>
      <dd>
        In diesem Fall ist es möglich, dass die Umlaute nicht richtig konvertiert werden. Prinzipiell gibt es zwei Möglichkeiten:
        Du verwendest in LaTeX den UTF-8-Zeichensatz (z.B. mit dem Befehl <code class="latex">\usepackage[utf8]{inputenc}</code>).
        Dann solltest du <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> anweisen, BiBTeX als UTF-8 zu exportieren. In allen anderen Fällen sollte <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> die Sonderzeichen
        als LaTeX-Befehle exportieren.<br/>
        Du kannst dieses Verhalten in deinem Nutzerprofil einstellen. Im Punkt <q>Voreinstellungen</q> (de-)markiere die Einstellung
        <q>Exportiere BiBTeX als UTF-8</q>.
      </dd>
      <dt>Wenn ich BiBTeX importiere, beschwert <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> sich, dass Pflichtfelder fehlen würden. In der Detailansicht des importierten Eintrags fehlen Felder.</dt>
      <dd>
        Möglicherweise hatte der BiBTeX-Eintrag einen Publikationstyp, den <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> nicht kennt. Der Publikationstyp steht ganz zu Beginn
        eines BiBTeX-Eintrags, z.B. <code class="bibtex">@article</code>. Ist der Typ nicht bekannt, wird er fehlerhaft in die Datenbank geschrieben.<br/>
        Bis dieses Problem behoben ist, ist eine Lösung, den BiBTeX-Eintrag zu bearbeiten und einen Typ zu verwenden, den <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr>
        versteht (der einfachste Typ ist <code class="bibtex">@misc</code>).
      </dd>
      <dt>Eine Publikation soll nur von mir gesehen werden können. Ist das möglich?</dt>
      <dd>
        Ja, <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> erlaubt die Vergabe von Rechten, mit denen du genau kontrollieren kannst, wer was mit deiner Veröffentlichung
        machen kann. Wenn du dich mit Linux auskennst: Die Rechtevergabe funktioniert nach einem ähnlichen Muster.<br/>
        <img src="http://www-cgi.uni-r.de/~stm01875/puma//faq/images/rights.png" alt="[screenshot]" class="screenshot" />
        Neben dem Titel einer Veröffentlichung findest du zwei Ampelgrafiken, die die aktuellen Rechte für <q>lesen</q> (r) und
        <q>bearbeiten</q> (e) anzeigen. Ein Klick auf diese Symbole führt auf eine Bearbeitungsseite, auf der du genau einstellen
        kannst, wer dein Paper sehen und bearbeiten darf.<br/>
        <strong>Tipp:</strong> Das funktioniert auch, jeweils unabhängig, für einzelne Kommentare, Anhänge und auch ganze Themen.
      </dd>
      <dt>Ich möchte nur bestimmte Publikationen exportieren.</dt>
      <dd>
        <img src="http://www-cgi.uni-r.de/~stm01875/puma//faq/images/merkbutton.jpg" alt="[screenshot]" class="screenshot" />
        Du kannst eine Merkliste anlegen. Klicke dazu einfach bei den Publikationen, die du exportieren willst, auf das Ordnersymbol rechts.
        Sobald du fertig bist, wähle in der Navigation <q>Meine Merkliste</q>. Dort hast du verschiedene Auswahlmöglichkeiten, die
        gemerkten Veröffentlichungen weiterzuverwenden.
      </dd>
      <dt>Kann ich ein neues Thema aus einer Gruppe von Publikationen erstellen?</dt>
      <dd>
        Ja, indem du die Veröffentlichungen vorher auf deine Merkliste setzt (siehe vorhergehende Frage). Dort hast du dann die
        Möglichkeit, alle gemerkten Veröffentlichungen einem neuen Thema zuzuweisen.
      </dd>
    </dl>
  </div>
  <div class="section">
    <h3>Mein Benutzerprofil</h3>
    <dl class="faqlist">
      <dt>Wie kann ich die Sprache umschalten?</dt>
      <dd>
        <img src="http://www-cgi.uni-r.de/~stm01875/puma//faq/images/Sprache.png" alt="[screenshot]" class="screenshot" />
        Rechts oben neben der Suchmaske gibt es ein Auswahlfeld <q>Sprache</q>. Dort kannst du zwischen englisch und deutsch umschalten.
      </dd>
      <dt>Wenn ich versuche, mich anzumelden, kommt die Fehlermeldung <q>Die Anfrage wird unendlich umgeleitet</q>.</dt>
      <dd>
        Das Problem ist, dass der Browser das Cookie von <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> nicht akzeptiert. Die Authentifizierung wird jedoch aus mehreren Gründen
        über Cookies realisiert.<br/>
        Die Lösung ist, dem Browser für <abbr class='puma' title='Publication Management'>Puma.<em>&Phi;</em></abbr> mitzuteilen, Cookies zu akzeptieren. Üblicherweise gibt es dazu eine Option in
        den Bereichen Extras &rarr; Internetoptionen &rarr; Sicherheit (IE), Extras &rarr; Einstellungen &rarr; Datenschutz (Firefox) oder Bearbeiten &rarr; 
        Einstellungen &rarr; Sicherheit (Safari).
      </dd>
    </dl>
  </div>
</div>
