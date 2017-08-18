package chat;

public class getJIDS 
{
	public  String[] getPartsJID(String jid) 
		{
			String[] parts = new String[3];
			String node = null , domain, resource;
			if (jid == null) {
				return parts;
			}

			int atIndex = jid.indexOf("@");
			int slashIndex = jid.indexOf("/");

			// Node
			if (atIndex > 0) {
				node = jid.substring(0, atIndex);
			}

			// Domain
			if (atIndex + 1 > jid.length()) {
				throw new IllegalArgumentException("JID with empty domain not valid");
			}
			if (atIndex < 0) {
				if (slashIndex > 0) {
					domain = jid.substring(0, slashIndex);
				}
				else {
					domain = jid;
				}
			}
			else {
				if (slashIndex > 0) {
					domain = jid.substring(atIndex + 1, slashIndex);
				}
				else {
					domain = jid.substring(atIndex + 1);
				}
			}
			// Resource
			if (slashIndex + 1 > jid.length() || slashIndex < 0) {
				resource = null;
			}
			else {
				resource = jid.substring(slashIndex + 1);
			}
			parts[0] = node;
			parts[1] = domain;
			parts[2] = resource;
			return parts;
		}
		
} 
