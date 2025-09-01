import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { getActiveMenuItem } from "@/config/admin_menu";
import StateManagement from "@/components/content/StateManagement";

function LocationState() {
  const menuItems = getActiveMenuItem('/dashboard/admin/location/state');

  return (
    <DashboardLayout
      title="State Management"
      userRole="admin"
      menuItems={menuItems}
    >
      <StateManagement />
    </DashboardLayout>
  );
}

export default LocationState;
