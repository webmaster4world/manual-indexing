<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:fox="http://xml.apache.org/fop/extensions">
<xsl:output encoding="utf-8" method="xml"/>


<xsl:variable name="festgrau">#595959</xsl:variable>
<xsl:variable name="hellgrau">#808080</xsl:variable>
<xsl:variable name="rotmarker">#EC0000</xsl:variable>

 <xsl:attribute-set name="pubb_daten_css">
  <xsl:attribute name="space-befor">20pt</xsl:attribute>
  <xsl:attribute name="space-after">10pt</xsl:attribute>
  <xsl:attribute name="text-align">left</xsl:attribute>
  <xsl:attribute name="font-size">15pt</xsl:attribute>
  <xsl:attribute name="color"><xsl:value-of select="$hellgrau"/></xsl:attribute>
</xsl:attribute-set>


<xsl:attribute-set name="zelledaten">
  <xsl:attribute name="padding-after">8pt</xsl:attribute>
  <xsl:attribute name="padding-before">8pt</xsl:attribute>
  <xsl:attribute name="border-after-width">0.1pt</xsl:attribute>
  <xsl:attribute name="border-after-color"><xsl:value-of select="$festgrau"/></xsl:attribute>
  <xsl:attribute name="border-after-style">solid</xsl:attribute>
</xsl:attribute-set>

<xsl:attribute-set name="daten_css">
  <xsl:attribute name="text-align">left</xsl:attribute>
  <xsl:attribute name="font-size">8pt</xsl:attribute>
  <xsl:attribute name="color"><xsl:value-of select="$festgrau"/></xsl:attribute>
</xsl:attribute-set>

<xsl:attribute-set name="feldnamen_css">
  <xsl:attribute name="text-align">left</xsl:attribute>
  <xsl:attribute name="font-size">8pt</xsl:attribute>
  <xsl:attribute name="color"><xsl:value-of select="$rotmarker"/></xsl:attribute>
</xsl:attribute-set>

<xsl:attribute-set name="felddaten_css">
  <xsl:attribute name="text-align">left</xsl:attribute>
  <xsl:attribute name="font-size">6pt</xsl:attribute>
  <xsl:attribute name="color"><xsl:value-of select="$festgrau"/></xsl:attribute>
</xsl:attribute-set>

<xsl:attribute-set name="titel_css">
  <xsl:attribute name="text-align">left</xsl:attribute>
  <xsl:attribute name="font-size">18pt</xsl:attribute>
  <xsl:attribute name="color"><xsl:value-of select="$rotmarker"/></xsl:attribute>
</xsl:attribute-set>

<xsl:attribute-set name="tabellen_titel_css">
  <xsl:attribute name="space-befor">20pt</xsl:attribute>
  <xsl:attribute name="space-after">10pt</xsl:attribute>
  <xsl:attribute name="text-align">left</xsl:attribute>
  <xsl:attribute name="font-size">11pt</xsl:attribute>
  <xsl:attribute name="color"><xsl:value-of select="$rotmarker"/></xsl:attribute>
</xsl:attribute-set>


<xsl:variable name="bigcolumncount" select="/root/@maxcoolnr" />


<xsl:template match="/">

<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format" >

<!-- landscape or vertical? -->

<xsl:choose>
  <xsl:when test="$bigcolumncount &gt; 8">
   <!-- landscape -->
    <fo:layout-master-set>
   <fo:simple-page-master master-name="simple" page-height="21cm" page-width="30cm"
        margin-top="1cm" margin-bottom="0.3cm" margin-left="1.2cm" margin-right="1.2cm">
      <fo:region-body margin-bottom="2cm"/>
      <fo:region-after extent="16pt"/>
   </fo:simple-page-master>
 </fo:layout-master-set>
  </xsl:when>
  <xsl:otherwise>
  <!-- vertical -->
    <fo:layout-master-set>
   <fo:simple-page-master master-name="simple" page-height="29.7cm" page-width="21cm"
        margin-top="1cm" margin-bottom="0.3cm" margin-left="1.4cm" margin-right="1.2cm">
      <fo:region-body margin-bottom="2cm"/>
      <fo:region-after extent="16pt"/>
   </fo:simple-page-master>
 </fo:layout-master-set>
  </xsl:otherwise>
 </xsl:choose>
 
 

 
 

 <fo:page-sequence master-reference="simple">
 <fo:static-content flow-name="xsl-region-after">



      <fo:block>
	  <xsl:attribute name="text-align">end</xsl:attribute>
	<xsl:attribute name="font-size">10pt</xsl:attribute>
	<xsl:attribute name="font-family">Helvetica</xsl:attribute>
	<xsl:attribute name="color"><xsl:value-of select="$festgrau"/></xsl:attribute>
	<xsl:attribute name="padding-before">10pt</xsl:attribute>
    Page <fo:page-number/>
    </fo:block>
   </fo:static-content>  
   
   <fo:flow flow-name="xsl-region-body">
   
   <fo:block>
   <xsl:attribute name="text-align">right</xsl:attribute>
   <xsl:attribute name="font-size">10pt</xsl:attribute>
   <xsl:attribute name="font-family">Helvetica</xsl:attribute>
    <xsl:attribute name="color"><xsl:value-of select="$hellgrau"/></xsl:attribute>
    <xsl:attribute name="space-after">11pt</xsl:attribute>
    <xsl:attribute name="padding-before">0pt</xsl:attribute>
    <xsl:attribute name="padding-after">0pt</xsl:attribute>
    Powered by apache FOP / on <xsl:value-of select="/root/@userdate"/>
    </fo:block>
   
	<fo:block>
	<xsl:attribute name="color"><xsl:value-of select="$rotmarker"/></xsl:attribute>
	<xsl:attribute name="font-size">18pt</xsl:attribute>
	<xsl:attribute name="font-family">Helvetica</xsl:attribute>
    <xsl:attribute name="space-after">3pt</xsl:attribute>
    <xsl:attribute name="padding-before">0pt</xsl:attribute>
    <xsl:attribute name="padding-after">8pt</xsl:attribute>
	<xsl:value-of select="/root/@exportfrom"/>
    </fo:block>
   
     <xsl:call-template name="tabellen_liste"></xsl:call-template>
   




  <fo:block>
  <fo:external-graphic scaling="uniform" width="3cm" src="http://ciz.ch/images/ppk-web-logo.png"/>
  </fo:block>
  <fo:block>
   <xsl:attribute name="text-align">right</xsl:attribute>
   <xsl:attribute name="font-size">25pt</xsl:attribute>
   <xsl:attribute name="font-family">Helvetica</xsl:attribute>
    <xsl:attribute name="color"><xsl:value-of select="$hellgrau"/></xsl:attribute>
    <xsl:attribute name="space-after">11pt</xsl:attribute>
    <xsl:attribute name="padding-before">-40pt</xsl:attribute>
    <xsl:attribute name="padding-after">60pt</xsl:attribute>
     PPK-Webprogramm
    </fo:block>
    
     <xsl:element name="fo:block" use-attribute-sets="pubb_daten_css">
     CH-Gordevio - <fo:basic-link external-destination="http://www.ciz.ch/">www.ciz.ch</fo:basic-link> 
     - <fo:basic-link external-destination="http://www.swisse.ch/">www.swisse.ch</fo:basic-link>
     </xsl:element>
     <xsl:element name="fo:block" use-attribute-sets="pubb_daten_css">
     PC-SERVICE - WEBPROGRAMM - SCREENDESIGN
     </xsl:element>
     <xsl:element name="fo:block" use-attribute-sets="pubb_daten_css">
 	 SERVER-HOUSING - VIRTUAL-SERVER - INTERNET PHONE
     </xsl:element>
     <xsl:element name="fo:block" use-attribute-sets="pubb_daten_css">
 	 Screen/Server Application on Window / Mac / Linux
     </xsl:element>
     <xsl:element name="fo:block" use-attribute-sets="pubb_daten_css">
 	 Phone 041 / (0)91 753 20 66  - Fax Phone 041 / (0)91 753 20 69
     </xsl:element>
     
     
   </fo:flow>
  </fo:page-sequence>
</fo:root>
</xsl:template>



<xsl:template name="tabellen_liste">
<xsl:for-each select="/root/table">
<xsl:element name="fo:block" use-attribute-sets="tabellen_titel_css">
<xsl:call-template name="uppercase"><xsl:with-param name="word" select="@name" /></xsl:call-template>
</xsl:element>
<xsl:element name="fo:block" use-attribute-sets="daten_css">
Update <xsl:value-of select="/root/@userdate"/>
</xsl:element>
<xsl:call-template name="tabellen_kopf">
<xsl:with-param name="tabelle" select="@name" />
<xsl:with-param name="tnr" select="position()" />
</xsl:call-template>
</xsl:for-each>
</xsl:template>





<xsl:template name="tabellen_kopf">
<xsl:param name="tabelle" />
<xsl:param name="tnr" />

<xsl:comment>
name <xsl:value-of select="$tabelle"/> position <xsl:value-of select="$tnr"/>
</xsl:comment>

<fo:table break-after="page">
<xsl:for-each select="/root/table[$tnr]/rows[1]/row">
<fo:table-column column-width="2cm"/>
</xsl:for-each>
<fo:table-body>

<xsl:for-each select="/root/table[$tnr]/rows[1]">
<fo:table-row>
<xsl:for-each select="./row">
<xsl:element name="fo:table-cell" use-attribute-sets="zelledaten">
<xsl:element name="fo:block" use-attribute-sets="feldnamen_css">
<xsl:value-of select="@name"/>
</xsl:element>
</xsl:element>
</xsl:for-each>
</fo:table-row>
</xsl:for-each>

<xsl:for-each select="/root/table[$tnr]/rows">
<fo:table-row>
<xsl:for-each select="./row">
<xsl:element name="fo:table-cell" use-attribute-sets="zelledaten">
<xsl:element name="fo:block" use-attribute-sets="felddaten_css">
<xsl:value-of select="."/>
</xsl:element>
</xsl:element>
</xsl:for-each>
</fo:table-row>
</xsl:for-each>



</fo:table-body>
</fo:table>
</xsl:template>













<xsl:template name="lowercase"><xsl:param name="word" /><xsl:value-of select="translate($word, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')"/></xsl:template>
<xsl:template name="uppercase"><xsl:param name="word" /><xsl:value-of select="translate($word, 'abcdefghijklmnopqrstuvwxyz','ABCDEFGHIJKLMNOPQRSTUVWXYZ')"/></xsl:template>


<xsl:template match="*">
</xsl:template>

</xsl:stylesheet>
