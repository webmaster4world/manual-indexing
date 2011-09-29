<xsl:stylesheet version="1.0" 
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns="urn:schemas-microsoft-com:office:spreadsheet" 
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
xmlns:x="urn:schemas-microsoft-com:office:excel" 
xmlns:x2="http://schemas.microsoft.com/office/excel/2003/xml" 
xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" 
xmlns:o="urn:schemas-microsoft-com:office:office" 
xmlns:html="http://www.w3.org/TR/REC-html40" 
xmlns:c="urn:schemas-microsoft-com:office:component:spreadsheet">

<!-- namenspace deklaration -->
<!-- output encoding iso latin oder utf-8 -->

<xsl:output method="text" encoding="ISO-8859-1"/>

<xsl:variable name="anzahl_tabellen" select="count(//ss:Worksheet)" />
<xsl:variable name="anzahl_zeilen" select="count(//ss:Data)" />

<xsl:param name="acapo">
<xsl:text>
	
</xsl:text>
</xsl:param>
	
<xsl:param name="spazio">
<xsl:text>  </xsl:text>
</xsl:param>	


<xsl:template match="/">
BEGIN TRANSACTION;
##### total tables <xsl:value-of select ="$anzahl_tabellen"/> #####
##### total rows <xsl:value-of select ="$anzahl_zeilen"/> #####
<xsl:call-template name="tabellen_plural" />
COMMIT;
</xsl:template>


<xsl:template name="tabellen_plural">
<xsl:for-each select="//ss:Worksheet">
#####################################
##### Create table command  start tablenr.(<xsl:value-of select="position()" />) table name "<xsl:value-of select="@ss:Name" />" #####
<xsl:call-template name="create_table"><xsl:with-param name="tablename" select="@ss:Name" /><xsl:with-param name="tablenr" select="position()" /></xsl:call-template>
##### Create table command end  #####
#####################################

<xsl:call-template name="zeilens">
<xsl:with-param name="tablename" select="@ss:Name" />
<xsl:with-param name="tablenr" select="position()" />
</xsl:call-template>
</xsl:for-each></xsl:template>



<!-- create table name text or nummer from first row name...  -->
<xsl:template name="create_table"><xsl:param name="tablename" /><xsl:param name="tablenr" /><xsl:variable name="columnsnr" select="count(//ss:Worksheet[$tablenr]/ss:Table/ss:Row[1]/ss:Cell/ss:Data)" />
CREATE TABLE <xsl:value-of select="$tablename" /> (<xsl:for-each select="//ss:Worksheet[$tablenr]/ss:Table/ss:Row[2]/ss:Cell/ss:Data"><xsl:variable name="pos" select="position()" /><xsl:variable name="fieldtype" select="@ss:Type" /><xsl:call-template name="rowname"><xsl:with-param name="Cools" select="$pos" /><xsl:with-param name="Data" select="//ss:Worksheet[$tablenr]/ss:Table/ss:Row[1]/ss:Cell[$pos]/ss:Data" /></xsl:call-template><xsl:choose><xsl:when test="$fieldtype != 'Number'"> TEXT</xsl:when><xsl:otherwise> NUMMER</xsl:otherwise></xsl:choose><xsl:call-template name="separator"><xsl:with-param name="muststop" select="$columnsnr" /><xsl:with-param name="now" select="position()" /></xsl:call-template></xsl:for-each>);</xsl:template>
<!-- create table line complete insert.  //ss:Worksheet[$tablenr]/ss:Table/ss:Row[position() != 1] -->
<xsl:template name="zeilens"><xsl:param name="tablename" /><xsl:param name="tablenr" /><xsl:variable name="columnsnr" select="count(//ss:Worksheet[$tablenr]/ss:Table/ss:Row[1]/ss:Cell)" /><xsl:for-each select="//ss:Worksheet[$tablenr]/ss:Table/ss:Row[position() != 1]">INSERT INTO <xsl:value-of select ="$tablename"/> VALUES (<xsl:for-each select="./ss:Cell/ss:Data"><xsl:variable name="fieldtype" select="@ss:Type" /><xsl:choose><xsl:when test="$fieldtype != 'Number'">'<xsl:call-template name="encoding_data"><xsl:with-param name="Data" select="." /></xsl:call-template>'</xsl:when><xsl:otherwise><xsl:call-template name="encoding_data"><xsl:with-param name="Data" select="." /></xsl:call-template></xsl:otherwise></xsl:choose><xsl:call-template name="separator"><xsl:with-param name="muststop" select="$columnsnr" /><xsl:with-param name="now" select="position()" /></xsl:call-template></xsl:for-each>);
</xsl:for-each>
</xsl:template>
<!-- Check if data and set NULL / or data Here can insert text transform or regs  -->
<xsl:template name="encoding_data"><xsl:param name="Data" /><xsl:choose><xsl:when test="$Data != ''"><xsl:value-of select="$Data" /></xsl:when><xsl:otherwise>NULL</xsl:otherwise></xsl:choose></xsl:template>
<!-- separator , on field but on end ""  not  -->
<xsl:template name="separator"><xsl:param name="muststop" /><xsl:param name="now" /><xsl:choose><xsl:when test="$muststop = $now"></xsl:when><xsl:otherwise>,</xsl:otherwise></xsl:choose></xsl:template>

<xsl:template name="rowname"><xsl:param name="Data" /><xsl:param name="Cools" /><xsl:choose><xsl:when test="$Data != ''"><xsl:value-of select="$Data" /></xsl:when><xsl:otherwise>NO_NAME<xsl:value-of select ="$Cools"/></xsl:otherwise></xsl:choose></xsl:template>


<!-- columnsnr (<xsl:value-of select="$muststop" />)-(<xsl:value-of select="$now" />)  -->
<!-- stop not declared node!  -->
<xsl:template match="*">
</xsl:template>

</xsl:stylesheet>
