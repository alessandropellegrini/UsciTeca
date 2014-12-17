UsciTeca
========

L'UsciTeca Zona Cassiopea è un semplice database di posti per uscite e campi Scout.

Basato su PHP, Javascript ed AJAX, utilizza in modo massiccio le API di Google Maps v3 per la visualizzazione dei posti.
È stato integrato anche il supporto per la generazione di report PDF delle ricerche effettuate nel database.

Il codice è molto orientato all'utilizzo sul sito http://www.scoutcassiopea.org dal momento che nasce esclusivamente per l'integrazione con quel sito. Ad esempio, il software controlla se si è eseguito il login sul portale Word Press.

Tuttavia questa funzionalità è facilmente escludibile in fase di configurazione


## Installazione

* Creare un database MySql secondo lo schema in `database.sql`
* Impostare le variabili di connessione al database (ed eventualmente l'integrazione con Word Press) nel file `config.php`

