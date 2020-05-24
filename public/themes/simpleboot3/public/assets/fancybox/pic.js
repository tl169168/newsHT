//Í¼Æ¬°´±ÈÀýËõ·Å
function setimg(ImgD,w,h,url)
{
	var image=new Image(); 
	var iwidth = w;
	var iheight = h;
	image.onload=function()
	{
		ImgD.onload=null;
		ImgD.src=url;	
		if(image.width>0 && image.height>0)
		{		
			if(image.width/image.height>= iwidth/iheight)
			{ 
				if(image.width>iwidth)
				{   
					ImgD.width=iwidth;
				}
				else
				{ 
					ImgD.width=image.width
				}
				//ImgD.alt=image.width+"¡Á"+image.height; 
				} 
			else
			{ 
				if(image.height>iheight)
				{
					ImgD.height=iheight; 
				}
				else
				{ 
					ImgD.width=image.width;
				}
			//ImgD.alt=image.width+"¡Á"+image.height; 
			}
		}
	}	
	image.src=url;
}