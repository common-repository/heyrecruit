=== Heyrecruit ===
Contributors: heyrecruit
Requires at least: 6.1.1
Tested up to: 6.6.2
Requires PHP: 7.4
Stable tag: 1.3.6
License: GPLv2

Das offizielle Heyrecruit-Plugin lässt Dich mithilfe von Shortcodes die Karriereseite und Stellenanzeigen Deiner Firma in Wordpress integrieren.

== Description ==
Das offizielle Heyrecruit-Plugin lässt Dich mithilfe von Shortcodes die Karriereseite und Stellenanzeigen Deiner Firma in Deine Wordpress-Seite integrieren. Deine Heyrecruit-Client-ID und Dein Secret-Key ist alles, was Du zum Einrichten des Plugins benötigst.

Lege eine Keycolor in den Plugin-Einstellungen fest, um die Heyrecruit-Elemente Deinen Unternehmensfarben anzupassen.

Füge Deinen Google-Maps-API-Key in den Plugin-Einstellungen hinzu, um eine Map auf Deiner Karriereseite anzeigen zu lassen.

Style Deine Heyrecruit-Seiten nach Belieben per CSS mithilfe von vordefinierten Klassen.

Bei Fragen und für Feedback schreibe uns gerne an info@heyrecruit.de.


==Universelle Shortcodes:==
**[hr_css_files]** - Dieser Shortcode lädt CSS-Dateien, die von dem Plugin verwendet werden, um das Layout und das Design der Stellenanzeigen oder der Übersichtsseite zu gestalten.

**[hr_js_files]** - Dieser Shortcode lädt JS-Dateien, die von dem Plugin verwendet werden, um interaktive Funktionen auf der Seite zu ermöglichen. Diese können z.B. für das Filtern oder die Jobsuche genutzt werden.

**[hr_company_header]** - Dieser Shortcode lädt die Firmeninfos, die in den Plugin-Einstellungen hinterlegt wurden. Dies können z.B. das Firmenlogo, die Adresse, die Telefonnummer oder die E-Mail-Adresse des Unternehmens sein.

**[hr_company_description]** - Dieser Shortcode lädt die Firmenbeschreibung, die in den Plugin-Einstellungen hinterlegt wurde.

**[hr_social_links]** - Dieser Shortcode lädt die Verlinkungen zu den hinterlegten Sozialen-Medien. Dies können z.B. die Adresse der Website, Facebook, Xing etc sein.

==Shortcodes für die Übersichtseite:==
**[hr_jobs_map]** - Dieser Shortcode lädt Google Maps, falls diese Funktion in den Plugin-Einstellungen aktiviert wurde. Die Karte zeigt den Standort der einzelnen Jobangebote auf der Übersichtsseite an.
**[hr_jobs_map departments="Produktion;Konstruktion"]** - Zeigt eine Kartenansicht der Stellenanzeigen für die Abteilungen "Produktion" und "Konstruktion" an. Es sind mehrere Begriffe durch Semikolon-Trennung zulässig.
**[hr_jobs_map employments="Teilzeit"]** - Zeigt eine Kartenansicht der Stellenanzeigen für die Beschäftigungsart "Teilzeit" an. Es sind mehrere Begriffe durch Semikolon-Trennung zulässig.
**[hr_jobs_map internal_title="Techniker"]** - Zeigt eine Kartenansicht der Stellenanzeigen mit dem internen Titel "Techniker" an. Es ist nur ein Begriff für den Parameter zulässig.
**[hr_jobs_map locations="123;456;789"]** - Zeigt eine Kartenansicht der Stellenanzeigen mit den Location-IDs "123", "456" und "789" an. Es sind mehrere Begriffe durch Semikolon-Trennung zulässig.
**[hr_jobs_map address="Frankfurt am Main"]** - Zeigt eine Kartenansicht der Stellenanzeigen mit der Adresse "Frankfurt am Main" an. Es ist nur ein Begriff für den Parameter zulässig.

**[hr_filter_options]** - Dieser Shortcode lädt die Filteroptionen auf der Übersichtsseite. Hier können die Besucher nach bestimmten Kriterien wie z.B. Ort, Arbeitszeit oder Gehalt filtern, um passende Jobs zu finden.
**[hr_filter_options departments="Produktion;Konstruktion"]** - Zeigt Filteroptionen für die Abteilung "Produktion" an. Das Wort "Produktion" ist ein Beispiel. Mehrere Begriffe werden, wie folgt, durch ein Semikolon getrennt: [hr_filter_options departments="Produktion;Konstruktion"].
**[hr_filter_options employments="Teilzeit"]** - Zeigt Filteroptionen für die Beschäftigungsart "Teilzeit" an. Es sind mehrere Begriffe durch Semikolon-Trennung zulässig.
**[hr_filter_options internal_title="Techniker"]** - Zeigt Filteroptionen mit dem internen Titel "Techniker" an. Es ist nur ein Begriff für den Parameter zulässig.
**[hr_filter_options locations="123;456;789"]** - Zeigt Filteroptionen mit den Location-IDs "123", "456" und "789" an. Es sind mehrere Begriffe durch Semikolon-Trennung zulässig.
**[hr_filter_options address="Frankfurt am Main"]** - Zeigt Filteroptionen mit der Adresse "Frankfurt am Main" an. Es ist nur ein Begriff für den Parameter zulässig.

**[hr_jobs]** - Dieser Shortcode lädt die Stellenanzeigen auf der Übersichtsseite. Hier werden alle verfügbaren Jobs in einer Tabelle aufgelistet.
**[hr_jobs departments="Produktion;Konstruktion"]** - Zeigt Stellenanzeigen in Tabellenform für die Abteilungen "Produktion" an. Das Wort "Produktion" ist ein Beispiel. Mehrere Begriffe werden, wie folgt, durch ein Semikolon getrennt: [hr_jobs departments="Produktion;Konstruktion;IT"].
**[hr_filter_options employments="Teilzeit"]** - Zeigt Stellenanzeigen in Tabellenform für die Beschäftigungsart "Teilzeit" an. Es sind mehrere Begriffe durch Semikolon-Trennung zulässig.
**[hr_filter_options internal_title="Techniker"]** - Zeigt Stellenanzeigen in Tabellenform mit dem internen Titel "Techniker" an. Es ist nur ein Begriff für den Parameter zulässig.
**[hr_filter_options locations="123;456;789"]** - Zeigt Stellenanzeigen in Tabellenform mit den Location-IDs "123", "456" und "789" an. Es sind mehrere Begriffe durch Semikolon-Trennung zulässig.
**[hr_filter_options address="Frankfurt am Main"]** - Zeigt Stellenanzeigen in Tabellenform mit der Adresse "Frankfurt am Main" an. Es ist nur ein Begriff für den Parameter zulässig.

**[hr_jobs_list]** - Dieser Shortcode lädt die Stellenanzeigen auf der Übersichtsseite. Hier werden alle verfügbaren Jobs in einer Liste aufgelistet.
**[hr_jobs_list departments="Produktion;Konstruktion"]** - Zeigt eine Liste von Stellenanzeigen für die Abteilungen "Produktion" an. Das Wort "Produktion" ist ein Beispiel. Mehrere Begriffe werden, wie folgt, durch ein Semikolon getrennt: [hr_jobs_list departments="Produktion;Konstruktion"].
**[hr_filter_options employments="Teilzeit"]** - Zeigt eine Liste von Stellenanzeigen für die Beschäftigungsart "Teilzeit" an. Es sind mehrere Begriffe durch Semikolon-Trennung zulässig.
**[hr_filter_options internal_title="Techniker"]** - Zeigt eine Liste von Stellenanzeigen mit dem internen Titel "Techniker" an. Es ist nur ein Begriff für den Parameter zulässig.
**[hr_filter_options locations="123;456;789"]** - Zeigt eine Liste von Stellenanzeigen mit den Location-IDs "123", "456" und "789" an. Es sind mehrere Begriffe durch Semikolon-Trennung zulässig.
**[hr_filter_options address="Frankfurt am Main"]** - Zeigt eine Liste von Stellenanzeigen mit der Adresse "Frankfurt am Main" an. Es ist nur ein Begriff für den Parameter zulässig.


==Shortcodes für die Job-Detailseite:==
**[hr_job_header]** - Dieser Shortcode lädt die Kopfzeile der Stellenanzeige mit den wichtigsten Informationen wie Titel, Untertitel und Anzeigenbild.

**[hr_job_header_without_image]** - Dieser Shortcode lädt Titel, Untertitel ohne das Anzeigenbild.

**[hr_job_title]** - Dieser Shortcode lädt den Titel der Stellenanzeige auf der Detailseite.

**[hr_job_internal_title]** - Dieser Shortcode gibt den internen Titel der Stellenanzeige aus.

**[hr_job_sub_title]** - Dieser Shortcode lädt den Untertitel der Stellenanzeige auf der Detailseite.

**[hr_job_details]** - Dieser Shortcode lädt die Kopfzeile der Stellenanzeige mit den Informationen wie Abteilung, Gehalt, Adresse auf der Detailseite.

**[hr_job_employment]** - Dieser Shortcode lädt die Beschäftigungsart, z.B. Vollzeit oder Teilzeit, auf der Detailseite.

**[hr_job_department]** - Dieser Shortcode lädt die Abteilung, in der die Stelle ausgeschrieben ist, auf der Detailseite.

**[hr_job_allow_home_office]** - Dieser Shortcode lädt Informationen zur Möglichkeit von Homeoffice, z.B. ob diese bei der Stelle möglich ist oder nicht, auf der Detailseite.

**[hr_job_min_salary]** - Dieser Shortcode lädt die Information zum minimalen Gehalt, das bei der Stelle gezahlt wird, auf der Detailseite.

**[hr_job_max_salary]** - Dieser Shortcode lädt die Information zum maximalen Gehalt, das bei der Stelle gezahlt wird, auf der Detailseite.

**[hr_job_description]** - Dieser Shortcode lädt die Beschreibung der Stellenanzeige auf der Detailseite.

**[hr_job_sections]** - Dieser Shortcode lädt zusätzliche Abschnitte der Stellenanzeige auf der Detailseite, z.B. Informationen zum Unternehmen oder zu den Anforderungen an den Bewerber.

**[hr_select_location]** - Dieser Shortcode lädt eine Auswahl weiterer Arbeitsorte zur Stellenanzeige auf der Detailseite.

**[hr_job_form]** - Dieser Shortcode lädt das Bewerbungsformular auf der Detailseite.

**[hr_job_street]**, **[hr_job_house_number]**, **[hr_job_postal_code]**, **[hr_job_city]**, **[hr_job_state]**, **[hr_job_country]**, **[hr_job_address]** - Diese Shortcodes laden die Informationen zur Adresse des Arbeitgebers auf der Detailseite, wie z.B. Straße, Hausnummer, Postleitzahl

== Screenshots ==
1. Heyrecruit job page (light)
2. Heyrecruit career page (light)
3. Heyrecruit job page (dark)
4. Heyrecruit career page (dark)


== Changelog ==
= 1.3.6 =
* Für die Job-Detailseite wurde ein neuer Shortcode **[hr_job_internal_title]** erstellt. Dieser gibt den internen Titel der Stellenanzeige aus.
* Für die Shortcodes **[hr_jobs_map]**, **[hr_filter_options]**, **[hr_jobs]** und **[hr_jobs_list]** wurde der Parameter "employments" hinzugefügt. Dieser ist analog zum Parameter departments zu verwenden. Ein Beispiel hierfür wäre [hr_jobs_map employments="Teilzeit;Praktikum;Freelancer"]. Es kann nach einzelnen sowie mehreren Begriffen durch Semikolon-Trennung gefiltert werden.
* Für die Shortcodes **[hr_jobs_map]**, **[hr_filter_options]**, **[hr_jobs]** und **[hr_jobs_list]** wurde der Parameter "internal_title" hinzugefügt. Dieser ist analog zum Parameter departments/employments zu verwenden. Bei diesem Parameter darf nur ein einzelner Begriff verwendet werden, mehrere Begriffe sind nicht zulässig.
* Für die Shortcodes **[hr_jobs_map]**, **[hr_filter_options]**, **[hr_jobs]** und **[hr_jobs_list]** wurde der Parameter "locations" und "address" hinzugefügt. Es kann nur ein Parameter, entweder locations oder address, aktiv sein. Für den Parameter "locations" können eine oder mehrere IDs durch Semikolon-Trennung verwendet werden. Ein Beispiel hierfür wäre [hr_jobs_map locations="123;456;789"]. Für den Parameter "address" ist nur ein Begriff zulässig, also z.B. [hr_jobs_map address="Frankfurt am Main"].


= 1.3.5 =
* Leere Sections im Bewerbungsformular werden ausgeblendet
* Fehler beim Filtern nach Fachabteilung behoben

= 1.3.4 =
* Branche(n) in Fachabteilung(en) umbenannt.

= 1.3.3 =
Neuerungen
Shortcode-Filter hinzugefügt: Es ist nun möglich, die Shortcodes mit Filtern zu versehen, um spezifische Stellenanzeigen von bestimmten Fachabteilungen anzuzeigen. Die Fachabteilungen werden durch ein Semikolon (;) getrennt.
Verfügbare Shortcodes
**[hr_jobs_list departments="Produktion;Konstruktion"]**
Zeigt eine Liste von Stellenanzeigen für die Abteilungen Produktion und Konstruktion an.

**[hr_jobs departments="Produktion;Konstruktion;IT"]**
Zeigt Stellenanzeigen in Tabellenform für die Abteilungen Produktion, Konstruktion und IT an.

**[hr_filter_options departments="Produktion"]**
Zeigt Filteroptionen für die Abteilung Produktion an.

**[hr_jobs_map departments="Konstruktion"]**
Zeigt eine Kartenansicht der Stellenanzeigen für die Abteilung Konstruktion an.


Hinweise zur Nutzung
Um spezifische Fachabteilungen anzuzeigen, geben Sie die gewünschten Abteilungen in den Shortcodes mit einem Semikolon (;) getrennt an.
Die Shortcodes sind flexibel und ermöglichen es, mehrere Abteilungen gleichzeitig zu filtern und anzuzeigen.

= 1.3.2 =
* Das Heyrecruit-Plugin wird nun als eigenständiger Menüpunkt in Wordpress aufgeführt.
* Alle Einstellungen wurden in die verschiedenen Unterpunkte einsortiert. Der Punkt "Allgemein" & "Benutzeroptionen" beinhaltet alle bereits bestehenden Einstellungen.
* Der Punkt "Seiteneinstellungen" wurde hinzugefügt. Hier sind alle ID's der aktuell genutzten Seiten des Plugins hinterlegt.
* Die "Seiteneinstellungen" bieten nun eine Referenzierung einer Bestätigungsseite, per ID oder einer externen Seite per URL.
* Eine Bestätigungsseite wurde eingeführt. Diese wird bei Installation des Plugins nun ebenfalls erstellt.
* Unter dem Punkt "Seiteneinstellungen" können bequem per Button-Klick alle Seiten (Job-Übersichtsseite, Job-Detailseite & Bestätigungsseite) neu generiert werden. Die ID's der neuen Seiten werden automatisch referenziert. Die alten Seiten bleiben unreferenziert erhalten.
* Wird eine URL zu einer externen Bestätigungsseite angegeben, so wird diese priorisiert.
* Nach Neuinstallation des Plugins oder Neugenerierung der Seiten wird nun standardmäßig der Shortcode **[hr_jobs_list]** statt **[hr_jobs]** verwendet.

= 1.3.1 =
* Die Deklaration der JavaScript-Variablen wurde korrigiert.

= 1.3.0 =
* Erweiterung um den Shortcode **[hr_jobs_list]**. Neben der Ausgabe als Tabelle können hiermit die offenen Stellenanzeigen auch als Liste ausgegeben werden.
* Zusätzlich zur Keycolor deines Unternehmens, kann nun in den Einstellungen eine Hintergrundfarbe für spezifische Elemente, z.B. für die Boxen in der neu hinzugefügten Listenansicht, gewählt werden.
* Erweiterung der Pagination
* Stellenanzeigen werden nicht mehr gruppiert. Ab 11 Stellenanzeigen wird nun die erweiterte Pagination angezeigt.

= 1.2.1 =
* Kompatibilität mit neuerer Version der Heyrecruit-API und PHP v.7.4 hergestellt

= 1.2.0 =
* Aufschlüsselung des Shortcodes **[hr_company_info]** in **[hr_company_header]** & **[hr_company_description]** für eine noch individuellere Gestaltung.
* Aufsplittung des Job Headers in die Anzeigen-Details und Anzeigentitel, Anzeigensubtitel und Anzeigenbild.
* Kopfzeile wird nun mit und ohne Bild zur Verfügung gestellt.
* Soziale Medien erhalten einen eigenen Shortcode **[hr_social-links]**. Sie sind eigenständig auf Übersichtsseite und Detailseite, auch ohne die Firmenbeschreibung, verwendbar.
* Kompatibilität FontAwesome 6 Pro integriert


== Upgrade Notice ==
= 1.2.0 =
* Der Shortcode **[hr_company_info]** wird für eine individuellere Gestaltung durch **[hr_company_header]** & **[hr_company_description]** ersetzt.
Bitte passen Sie Ihre Seiten entsprechend an, falls Sie **[hr_company_info]** verwenden.
* Der Shortcode **[hr_job_header]** wird für eine bessere Gestaltung aufgesplittet. Die Job-Details sind in den **[hr_job_details]** Shortcut ausgelagert worden.
Bitte passen Sie Ihre Seiten entsprechend an, falls Sie **[hr_job_header]** verwenden.
* Der Job Header wird nun in zwei Varianten bereitgestellt. Mit und ohne Headerbild. Für die Variante ohne Bild nutzen Sie den Shortcode [hr_job_header_without_image].