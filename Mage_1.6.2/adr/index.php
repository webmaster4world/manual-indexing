<?php
if (is_file('adressen.xml')) {
  header('Content-type: text/html; charset=utf-8');
  header('Content-Disposition: attachment; filename="adressen.xml"');
  readfile('adressen.xml');
  unlink('adressen.xml');
  exit;
} else {
  echo 'Datei adressen.xml fehlt. <br />System, Import/Export, Profile. <br /><strong>Export Customers</strong> ausfuehren.';
}
