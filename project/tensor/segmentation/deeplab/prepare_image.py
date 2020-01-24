import sys
from PIL import Image, ImageOps

SIZE = 326, 326

def autocropImage(image, border = 0):
    r,g,b,_ = image.split()
    image = Image.merge('RGB', (r,g,b))
    image = ImageOps.invert(image)
    # Get the bounding box
    bbox = image.getbbox()
    # Crop the image to the contents of the bounding box
    image = image.crop(bbox)
    # print(image.size)
    image = ImageOps.invert(image)
    # Determine the width and height of the cropped image
    (width, height) = image.size

    # Add border
    width += border * 2
    height += border * 2
    
    # Create a new image object for the output image
    croppedImage = Image.new("RGB", (width, height), (0,0,0,0))
    # print(croppedImage.size)
    # Paste the cropped image onto the new image
    croppedImage.paste(image, (border, border))

    # Done!
    return croppedImage

def resizeImage(image):
    resizedImage = image.resize(SIZE)
    return resizedImage
    
if len(sys.argv) < 2:
    # Not enough arguments -- show usage information and exit
    print "Usage: " + sys.argv[0] + " infile"
    exit(1)

# Get input and output file names
infile = sys.argv[1]

# Open the input image
image = Image.open(infile)

# Do the cropping
image = autocropImage(image)

# Do the resizing
image = resizeImage(image)

# Save the output image
image.save(infile)