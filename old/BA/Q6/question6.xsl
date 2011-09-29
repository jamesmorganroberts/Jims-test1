<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">


  <html>
  
  <head>
  
  <link type="text/css" rel="stylesheet" media="screen" href="question6.css" />
 
  </head>
  
  
  <body>


<div id="headerContainer">

<div id="header">
<div id="baLogo"><h1>British Airways logo</h1></div>
<div id="londonLogo"><h1>Visit London logo</h1></div>
<div id="weatherReport">
<h2>London Today</h2>
Mostly Cloudy<br/>
Temp:19&#8451;<br/>
Wind:24mph SSW
</div>
<div id="weatherLogo"></div>

</div>


</div>

	<div id="main">
    
    
    


			 <div class="softBox">
        	
                <span class="softTop"></span>
                
                <div class="softContent">
                    <h2>Our favourite....Pubs and Bars</h2>
                    <p>Below is a selection of our favourite pubs and bars. Choose reviews to see what other customers think about the place.</p>
                </div>
                
                <span class="softBottom"></span>
        
       		</div>

	<div id="bars">




    <xsl:for-each select="favourites/bars/bar">
     <div class="barBox">
                 
          
            
            <img>
            <xsl:attribute name="src">
            <xsl:value-of select="//@src" /><xsl:value-of select="image" />
            </xsl:attribute>
            </img>  
                 
                 
                 
          	<div class="barText">
                 
            	<h2><xsl:value-of select="name"/></h2>
                <p>Soak up the sumptuous decor in this spacious Partisan themed bar in Soho. Expect to be dazzled by the ornate crystal chandeliers, velvet cushions and thousands of jewels!</p>
            
            </div>
                
            <div class="barList">      
               <ul>
               <li><a href="">Reviews</a></li>
               <li><a href="">www.jewelbar.co.uk</a></li>
               </ul>
           	</div>             
                
           	<span class="barBottom"></span>
                
    </div>

    </xsl:for-each>
     
  </div>
  
  				<div class="softBox">
        	
                <span class="softTop"></span>
                
                <div class="softContent">
                  
                 <h2>More favourite....Pubs and Bars</h2>
                  
                 
                    
             
                    
                    <xsl:for-each select="favourites/bars/otherbars/bar">

					
					<xsl:choose>
                    <xsl:when test="position() mod 4 = 1">
                    	
                        <xsl:text disable-output-escaping="yes">&lt;div class="listBox"&gt;</xsl:text>
                        <xsl:text disable-output-escaping="yes">&lt;ul&gt;</xsl:text>
                        
                        <li>
                        <a><xsl:attribute name="href">
                        <xsl:value-of select="websitelink"/></xsl:attribute> 
                        <xsl:value-of select="name"/> 
                        </a>
                        
                        </li>
                        
                    </xsl:when>
                    <xsl:when test="position()=last()">
                    
                    	<li>
                        <a><xsl:attribute name="href">
                        <xsl:value-of select="websitelink"/></xsl:attribute> 
                        <xsl:value-of select="name"/> 
                        </a>
                        
                        </li>
                        
                         <xsl:text disable-output-escaping="yes">&lt;/ul&gt;</xsl:text>
                        <xsl:text disable-output-escaping="yes">&lt;/div&gt;</xsl:text>
                       
                        
                    </xsl:when>
                   
                    <xsl:when test="position() mod 4 = 1-1">
                    	<li>
                         <a><xsl:attribute name="href">
                        <xsl:value-of select="websitelink"/></xsl:attribute> 
                        <xsl:value-of select="name"/> 
                        </a>
                        
                        </li>
                        
                         <xsl:text disable-output-escaping="yes">&lt;/ul&gt;</xsl:text>
                         <xsl:text disable-output-escaping="yes">&lt;/div&gt;</xsl:text>
                         
                        
                    </xsl:when>
                    <xsl:otherwise>
                        <li> 
                        <a><xsl:attribute name="href">
                        <xsl:value-of select="websitelink"/></xsl:attribute> 
                        <xsl:value-of select="name"/> 
                        </a>
                        </li>
                    </xsl:otherwise> 
                    </xsl:choose>
                    
               
                    
                    </xsl:for-each>
               
                    
                   </div> 
                  
              
                
                <span class="softBottom"></span>
                
                </div>
  
  
  
				<div class="softBox">
        	
                <span class="softTop"></span>
                
                <div class="softContent_centre">
                    <p>
                    <a href="#">British Airways</a> |
                    <a href="#">Visit London</a>
                    </p>
                </div>
                
                <span class="softBottom"></span>
                
                </div>
                
                
                
  
  
  </div>
  </body>
  </html>
  
  
  
</xsl:template>
</xsl:stylesheet>