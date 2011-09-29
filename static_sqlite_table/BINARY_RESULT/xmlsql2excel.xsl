<xsl:stylesheet version="1.0" 
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:msxsl="urn:schemas-microsoft-com:xslt" 
xmlns:ivis="urn:my-scripts" 
xmlns:trans="urn:translator" 
xmlns="urn:schemas-microsoft-com:office:spreadsheet" 
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
xmlns:x="urn:schemas-microsoft-com:office:excel" 
xmlns:x2="http://schemas.microsoft.com/office/excel/2003/xml" 
xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" 
xmlns:o="urn:schemas-microsoft-com:office:office" 
xmlns:html="http://www.w3.org/TR/REC-html40" 
xmlns:c="urn:schemas-microsoft-com:office:component:spreadsheet">

<xsl:output encoding="utf-8" standalone="no" omit-xml-declaration="yes" indent="yes" method="html"/>

<xsl:param name="breaks">
<xsl:text>
</xsl:text>
</xsl:param>
	
<xsl:param name="spaces">
<xsl:text>  </xsl:text>
</xsl:param>

<xsl:template match="/">
<html xmlns:msxsl="urn:schemas-microsoft-com:xslt" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:ivis="urn:my-scripts" xmlns:trans="urn:translator">
  <head><xsl:value-of select="$breaks"/>
    <META http-equiv="Content-Type" content="text/html; charset=utf-8" /><xsl:value-of select="$breaks"/>
    <title>Update <xsl:value-of select="/root/@userdate"/></title><xsl:value-of select="$breaks"/>
    <style><![CDATA[
			th { color:black; font-style:bold; font-size:14pt; }
			td { mso-style-name: normal; mso-numberformat:general;
				 vertical-align: bottom;
				font-family:arial; font-size:10pt; color:navy;}
			td.num { vnd.ms-excel.numberformat:#,##0.00; color:red; text-align: right; }
    ]]></style><xsl:value-of select="$breaks"/>
  </head><xsl:value-of select="$breaks"/>
  <xsl:value-of select="$spaces"/><body><xsl:value-of select="$breaks"/>
  <xsl:call-template name="tabellen_kopf"/>
  <xsl:value-of select="$spaces"/></body><xsl:value-of select="$breaks"/>
</html>
</xsl:template>



<xsl:template name="tabellen_kopf">
<table><xsl:value-of select="$breaks"/>
      <xsl:value-of select="$spaces"/><thead><xsl:value-of select="$breaks"/>
        <tr><xsl:value-of select="$breaks"/>
          <xsl:for-each select="/root/table[1]/rows[1]">
          <xsl:for-each select="./row">
          <xsl:value-of select="$spaces"/><xsl:value-of select="$spaces"/><th><xsl:value-of select="@name"/></th><xsl:value-of select="$breaks"/>
          </xsl:for-each>
          </xsl:for-each>
       </tr><xsl:value-of select="$breaks"/>
      <xsl:value-of select="$spaces"/></thead><xsl:value-of select="$breaks"/>
      <xsl:value-of select="$spaces"/><tbody><xsl:value-of select="$breaks"/>
          <xsl:for-each select="/root/table[1]/rows">
          <xsl:value-of select="$spaces"/><xsl:value-of select="$spaces"/><tr><xsl:value-of select="$breaks"/>
          <xsl:for-each select="./row">
                  <xsl:choose>
                  <xsl:when test="@type != 'digit'">
                   <xsl:value-of select="$spaces"/><xsl:value-of select="$spaces"/><td x:str="{.}"><xsl:value-of select="."/></td><xsl:value-of select="$breaks"/>
                  </xsl:when>
                  <xsl:otherwise>
                  <xsl:value-of select="$spaces"/><xsl:value-of select="$spaces"/><td class="num" x:num="{.}"><xsl:value-of select="."/></td><xsl:value-of select="$breaks"/>
                  </xsl:otherwise>
                 </xsl:choose>
          
          
          </xsl:for-each>
          <xsl:value-of select="$spaces"/><xsl:value-of select="$spaces"/></tr><xsl:value-of select="$breaks"/>
          </xsl:for-each>
      <xsl:value-of select="$spaces"/></tbody><xsl:value-of select="$breaks"/>
</table><xsl:value-of select="$breaks"/>
</xsl:template>




<xsl:template name="lowercase"><xsl:param name="word" /><xsl:value-of select="translate($word, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')"/></xsl:template>
<xsl:template name="uppercase"><xsl:param name="word" /><xsl:value-of select="translate($word, 'abcdefghijklmnopqrstuvwxyz','ABCDEFGHIJKLMNOPQRSTUVWXYZ')"/></xsl:template>


<xsl:template match="*">
</xsl:template>

</xsl:stylesheet>
